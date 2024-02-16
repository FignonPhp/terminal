# Terminal

Primary use case is to display messages in terminal with a nice look.

See [Fignon Framework for use case](https://github.com/FignonPhp/fignon).


## Installation

```bash
composer require fignon/terminal
```

## Usage

```php
use Fignon\Extra\Terminal;

$terminal = new Terminal();
$terminal->write('Hello World');

// Make a frame around the message with a color
$terminal->log($terminal->frame("Chinese: 你好，世界！ (Nǐ hǎo, shìjiè!)"), 'cyan', ['bold']);
```