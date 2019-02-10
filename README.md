# AI好棒棒（LINE Bot範例）
# v1 第一版

以ine-bot-sdk-php的EchoBot為基礎，修改Route.php，實現偽人工智慧以及自訂歡迎訊息。

## 安裝步驟

1. 下載LINE官方的PHP SDK及範例程式

`curl -sS https://getcomposer.org/installer | php # Install composer.phar`

`./composer.phar require linecorp/line-bot-sdk`

2. 切換到EchoBot範例程式的目錄底下

3. 安裝必要的套件（包括LINE PHP SDK和Slim框架）

`curl -sS https://getcomposer.org/installer | php # Install composer.phar`

`./composer.phar install`

4. 修改src/LINEBot/EchoBot/Setting.php的`channelToken`和`channelSecret`這兩行

5. 更新src/LINEBot/EchoBot/Route.php

## 相關網路資源

1. [line-bot-sdk-php](https://github.com/line/line-bot-sdk-php)
2. [LINE內建貼圖清單](https://devdocs.line.me/files/sticker_list.pdf)
3. [LINE內建Emoji顏文字清單](https://developers.line.biz/media/messaging-api/emoji-list.pdf)
