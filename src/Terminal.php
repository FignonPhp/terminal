<?php
declare(strict_types=1);

namespace Fignon\Extra;

/**
 * Interface to log terminal output with color and beautified style
 *
 * E.g.:
 *
 * $terminal = new Terminal();
 *
 * // Unstyled log
 * $terminal->log('Hello World');
 *
 * // Log with color
 * $terminal->log('Hello World', 'red');
 *
 * // Log with color and style
 * $terminal->log('Hello World', 'red', ['bold']);
 *
 * $terminal->log('Hello World', 'red', ['bold', 'underline']);
 *
 * $terminal->log('Hello World', 'red', ['bold', 'underline', 'blink']);
 *
 * // Use to display lists
 * $items = ["Item 1", "Item 2", "Item 3"];
 * $terminal->log($terminal->list($items));
 *
 * // Use to display frames
 * $terminal->log($terminal->frame("Ceci est un texte encadré"));
 *
 * // Use to display boxes
 * $terminal->log($terminal->box("Ceci est un texte encadré"));
 *
 * // Use to display emojis
 * $terminal->log($terminal->emoji("smile")); // Affiche un emoji souriant
 *
 * // Use to strike a message
 * $terminal->log($terminal->strike("Ceci est un texte barré"));
 *
 * // Use to print to the terminal
 * $terminal->toTerminal("This will be printed to the terminal or logged in a web environment.");
 *
 * // Support any characters set like chinese or japan language even russia
 * $terminal->log($terminal->frame2("China: 你好，世界!"), 'green', ['bold']);
 *
 * $terminal->log($terminal->frame2("French: Bonjour les français avec è à ù "), 'green', ['bold']);
 *
 * $terminal->log($terminal->frame("你好，世界!"), 'green', ['bold']);
 *
 * $terminal->log($terminal->frame("Hello World! == 你好，世界!"), 'blue', ['bold']);
 *
 * $terminal->log($terminal->frame("Je suis oK avec des acàs mé\^\$，世界"), 'dark', ['bold']);
 *
 * $terminal->log($terminal->frame("Russia: Привет, мир! (Privet, mir!)"), 'cyan', ['bold']);
 *
 * $terminal->log($terminal->frame("Hebrew: שלום, עולם! (Shalom, olam!)"), 'yellow', ['bold']);
 *
 * // Note: Hindi supported is not yet ok
 * $terminal->log($terminal->frame("Hindi: नमस्ते, दुनिया! (Namaste, duniya!)"), 'white', ['bold']);
 *
 * $terminal->log($terminal->frame("Arabic: مرحبا، العالم! (Marhaba, alam!)"), 'red', ['bold']);
 *
 * $terminal->log($terminal->frame("Japanese: こんにちは、世界！ (Konnichiwa, sekai!)"), 'red', ['bold']);
 *
 * $terminal->log($terminal->frame("Chinese: 你好，世界！ (Nǐ hǎo, shìjiè!)"), 'cyan', ['bold']);
 *
 * $terminal->log($terminal->frame("Korean: 안녕하세요, 세계! (Annyeonghaseyo, segye!)"), 'blue', ['bold']);
 */
class Terminal
{
    private $message = '';
    private $color = '';
    private $styles = [];
    private $frame = false;

    public function message($message)
    {
        $this->message = $message;
        return $this;
    }

    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    public function setStyle(...$styles)
    {
        $this->styles = $styles;
        return $this;
    }

    public function setFrame()
    {
        $this->frame = true;
        return $this;
    }

    public function send()
    {
        $this->message = $this->format($this->frame ? $this->frame($this->message) : $this->message, $this->color, $this->styles);
        $this->toTerminal($this->message);
        $this->message = '';
        $this->color = '';
        $this->styles = [];
        $this->frame = false;
    }


    /**
     * Log a message to the terminal with color and styles
     *
     * @param string $message The message to log
     * @param string $color The color of the message. Can be one of the following: black, dark_gray, blue, light_blue, green, light_green, cyan, light_cyan, red, light_red, purple, light_purple, brown, yellow, light_gray, white
     * @param string $style The style of the message. Can be one of the following: bold, dim, underline, blink, reverse, hidden
     * @param array $styles An array of styles
     * @return void Outputs the message to the terminal
     */
    public function log($message, $color = 'blue', $styles = []): void
    {
        $message = $this->format($message, $color, $styles);
        $this->toTerminal($message);
    }


    /**
     * Print a message to the standard output
     * @param mixed $message The message to print
     * @return void Outputs the message to the terminal
     */
    public function toTerminal($message)
    {
        error_log($message . PHP_EOL, 3, 'php://stdout');
    }
    /**
     * Format a message with color and styles
     *
     * @param string $message The message to format
     * @param string $color The color of the message. Can be one of the following: black, dark_gray, blue, light_blue, green, light_green, cyan, light_cyan, red, light_red, purple, light_purple, brown, yellow, light_gray, white
     * @param string $style The style of the message. Can be one of the following: bold, dim, underline, blink, reverse, hidden
     * @param array $styles An array of styles
     * @return string The formatted message
     */
    public function format($message, $color = 'green', $styles = [])
    {
        $message = $this->color($message, $color);
        foreach ($styles as $style) {
            $message = $this->style($message, $style);
        }
        return $message;
    }


    /**
     * Make a frame around a message
     *
     * Currently don't support Hindi message.
     *
     * @param string $message The message to frame
     * @return string The framed message
     */
    public function frame($message)
    {
        $length = mb_strwidth($message, 'UTF-8');
        return "┏" . str_repeat("━", $length + 2) . "┓\n┃ " . $message . " ┃\n┗" . str_repeat("━", $length + 2) . "┛";
    }

    public function frameMultiLine($message)
    {
        $lines = explode("\n", $message);
        $maxLength = max(array_map('mb_strwidth', $lines, array_fill(0, count($lines), 'UTF-8')));

        $border = "┏" . str_repeat("━", $maxLength + 2) . "┓";
        $output = $border . "\n";

        foreach ($lines as $line) {
            $output .= "┃ " . str_pad($line, $maxLength) . " ┃\n";
        }

        $output .= "┗" . str_repeat("━", $maxLength + 2) . "┛";

        return $output;
    }


    /**
     * Strike a message
     *
     * E.g.: $terminal->strike('Hello World');
     * // Outputs:  ̶H̶e̶l̶l̶o̶ ̶W̶o̶r̶l̶d̶
     */
    public function strike($message)
    {
        return "\033[9m" . $message . "\033[0m";
    }


    /**
     * Apply a style to a message
     *
     * @param string $message The message to apply the style to
     * @param string $style The style to apply. Can be one of the following: bold, dim, underline, blink, reverse, hidden
     * @return string The message with the style applied
     */
    public function style($message, $style = null)
    {
        if ($style === null) {
            return $message;
        }
        $styles = [
            'bold' => '1',
            'dim' => '2',
            'underline' => '4',
            'blink' => '5',
            'reverse' => '7',
            'hidden' => '8',
        ];
        if (isset($styles[$style])) {
            return "\033[" . $styles[$style] . "m" . $message . "\033[0m";
        }
        return $message;
    }

    /**
     * Apply a color to a message
     *
     * @param string $message The message to apply the color to
     * @param string $color The color to apply. Can be one of the following: black, dark_gray, blue, light_blue, green, light_green, cyan, light_cyan, red, light_red, purple, light_purple, brown, yellow, light_gray, white
     * @return string The message with the color applied
     */
    public function color($message, $color = null)
    {
        if (null === $color) {
            return $message;
        }
        $colors = [
            'black' => '0;30',
            'dark_gray' => '1;30',
            'blue' => '0;34',
            'light_blue' => '1;34',
            'green' => '0;32',
            'light_green' => '1;32',
            'cyan' => '0;36',
            'light_cyan' => '1;36',
            'red' => '0;31',
            'light_red' => '1;31',
            'purple' => '0;35',
            'light_purple' => '1;35',
            'brown' => '0;33',
            'yellow' => '1;33',
            'light_gray' => '0;37',
            'white' => '1;37',
        ];
        if (isset($colors[$color])) {
            return "\033[" . $colors[$color] . "m" . $message . "\033[0m";
        }
        return $message;
    }

    /**
     * Make a list from an array of items
     *
     * @param array $items The items to make a list from
     * @return string The list
     */
    public function list($items)
    {
        $output = "";
        foreach ($items as $item) {
            $output .= "• " . $item . "\n";
        }
        return $output;
    }

    /**
     * Get an emoji
     *
     * @param string $emoji The emoji to get
     * @return string The emoji
     */
    public function emoji($emoji)
    {
        $emojis = [
            'smile' => "\u{1F600}",
            'laugh' => "\u{1F602}",
            'wink' => "\u{1F609}",
            'grin' => "\u{1F601}",
            'sweat_smile' => "\u{1F605}",
            'joy' => "\u{1F602}",
            'rofl' => "\u{1F923}",
            'relaxed' => "\u{263A}",
            'blush' => "\u{1F60A}",
            'innocent' => "\u{1F607}",
            'slightly_smiling_face' => "\u{1F642}",
            'desktop_computer' => "\u{1F5A5}",
            'keyboard' => "\u{2328}",
            'computer_mouse' => "\u{1F5B1}",
        ];
        return $emojis[$emoji] ?? '';
    }

}
