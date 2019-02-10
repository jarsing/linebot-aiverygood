<?php

/**
 * Copyright 2016 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

namespace LINE\LINEBot\EchoBot;

use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\Exception\InvalidEventRequestException;
use LINE\LINEBot\Exception\InvalidSignatureException;
use LINE\LINEBot\Event\FollowEvent;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;

class Route
{
    public function register(\Slim\App $app)
    {
        $app->post('/callback', function (\Slim\Http\Request $req, \Slim\Http\Response $res) {
            /** @var \LINE\LINEBot $bot */
            $bot = $this->bot;
            /** @var \Monolog\Logger $logger */
            $logger = $this->logger;

            $signature = $req->getHeader(HTTPHeader::LINE_SIGNATURE);
            if (empty($signature)) {
                return $res->withStatus(400, 'Bad Request');
            }

            // Check request with signature and parse request
            try {
                $events = $bot->parseEventRequest($req->getBody(), $signature[0]);
            } catch (InvalidSignatureException $e) {
                return $res->withStatus(400, 'Invalid signature');
            } catch (InvalidEventRequestException $e) {
                return $res->withStatus(400, "Invalid event request");
            }

            foreach ($events as $event) {
                if ($event instanceof FollowEvent) {
                    $multipleMessageBuilder = new MultiMessageBuilder();
                    $multipleMessageBuilder->add(new TextMessageBuilder("感謝加我為好友，祝您闔家~~~\n諸事大吉\u{10005D} 萬事如意\u{100078}"))
                                           ->add(new StickerMessageBuilder(1, 4));
                    $resp = $bot->replyMessage($event->getReplyToken(), $multipleMessageBuilder);
                    $logger->info($resp->getHTTPStatus() . ': ' . $resp->getRawBody());
                    continue;
                }

                if (!($event instanceof MessageEvent)) {
                    $logger->info('Non message event has come');
                    continue;
                }

                if (!($event instanceof TextMessage)) {
                    $logger->info('Non text message has come');
                    continue;
                }

                $replyText = $event->getText();
                $replyText = str_replace('嗎', '', $replyText);
                $replyText = str_replace('?', '!', $replyText);
                $replyText = str_replace('？', '！', $replyText);
                $logger->info('Reply text: ' . $replyText);
                $resp = $bot->replyText($event->getReplyToken(), $replyText);
                $logger->info($resp->getHTTPStatus() . ': ' . $resp->getRawBody());
            }

            $res->write('OK');
            return $res;
        });
    }
}
