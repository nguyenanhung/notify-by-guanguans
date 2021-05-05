<?php

/**
 * This file is part of the guanguans/notify.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\Notify\Messages\DingTalk;

use Guanguans\Notify\Messages\Message;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionCardMessage extends Message
{
    protected $type = 'actionCard';

    protected $initOptions = [
        [
            'name' => 'pushType',
            'allowed_types' => ['string'],
            'allowed_values' => ['single', 'btns'],
            'default' => 'single',
        ],
    ];

    /**
     * TextMessage constructor.
     *
     * @param string $text
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);
    }

    /**
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $defaultOptions = $this->options;
        $diffOptions = configure_options(array_diff($options, $this->options), function (OptionsResolver $resolver) {
            $resolver->setDefined([
                'pushType',
                'title',
                'text',
                'btnOrientation',
                'singleTitle',
                'singleURL',
                'secret',
                'hideAvatar',
                'btns',
            ]);

            $resolver->setAllowedTypes('title', 'string');
            $resolver->setAllowedTypes('text', 'string');
            $resolver->setAllowedTypes('btnOrientation', 'string');
            $resolver->setAllowedTypes('singleTitle', 'string');
            $resolver->setAllowedTypes('singleURL', 'string');
            $resolver->setAllowedTypes('hideAvatar', 'string');
            $resolver->setAllowedTypes('btns', 'array');
            $resolver->setAllowedTypes('secret', 'string');

            $resolver->setAllowedValues('pushType', ['single', 'btns']);
        });

        $this->options = array_merge($this->options, $diffOptions);

        return $this;
    }

    public function getData()
    {
        if ('single' === $this->options['pushType']) {
            unset($this->options['btns']);
        }
        if ('btns' === $this->options['pushType']) {
            unset($this->options['singleTitle']);
            unset($this->options['singleURL']);
        }
        $data = [
            'msgtype' => $this->type,
            $this->type => $this->options,
        ];

        if ($this->options['secret']) {
            $data['timestamp'] = $time = time().sprintf('%03d', rand(1, 999));
            $data['sign'] = $this->getSign($this->options['secret'], $time);
        }

        return $data;
    }

    /**
     * @return string
     */
    protected function getSign(string $secret, int $timestamp)
    {
        $data = sprintf("%s\n%s", $timestamp, $secret);

        $hash = hash_hmac('sha256', $data, $secret, true);

        return urlencode(base64_encode($hash));
    }
}
