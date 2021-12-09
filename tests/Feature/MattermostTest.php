<?php

/**
 * This file is part of the guanguans/notify.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\Notify\Tests\Feature;

use Guanguans\Notify\Factory;
use Guanguans\Notify\Tests\TestCase;

class MattermostTest extends TestCase
{
    public function testMattermost()
    {
        $this->expectException(\GuzzleHttp\Exception\ClientException::class);

        Factory::mattermost()
            ->setHost('https://guanguans.cloud.mattermost.com')
            ->setToken('r7jezodttibgueijpahyyfh')
            ->setChannelId('sat5ohbs5byixd86tmxtk13')
            ->setMessage(
                new \Guanguans\Notify\Messages\MattermostMessage([
                    'message' => 'This is a testing.',
                    // 'is_pinned' => true,
                    // 'create_at' => 1639041968509,
                    // 'edit_at' => 1639041968509,
                    // 'root_id' => '',
                    // 'original_id' => '',
                    // 'type' => '',
                    // 'pending_post_id' => '1639041968509abc',
                    // 'participants' => null,
                    // 'props' => ['key' => 'value'],
                    // 'file_ids' => ['o3x4y157jff5xydf5m91bft1oo'],
                ])
            )
            ->send();
    }
}
