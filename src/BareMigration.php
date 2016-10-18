<?php
/**
 * Command-line tool to generate bare CodeIgniter migration file
 *
 *    php ci bare:migration [name]
 *
 * @package     BareMigration
 * @author      Sithu K. <cithukyaw@gmail.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE
 */

/**
 * CodeIgniter bare migration file generator
 */
class BareMigration
{
    /** @var string The command name **/
    private $command;
    /** @var string The string used in migration file name **/
    private $name;
    /** @var integer No of arguments passed to script **/
    protected $argc;
    /** @var array Array of arguments passed to script **/
    protected $argv;

    /**
     * Constructor
     */
    public function __construct()
    {
        global $argv;
        $this->argv = array_slice($argv, 1);
        $this->argc = count($this->argv) - 1;

        $this->command = strtolower(array_shift($this->argv));
        if ($this->command !== 'bare:migration') {
            die('Command is not valid, e.g., php ci bare:migration add_new_post_table');
        }

        $this->name = array_shift($this->argv);
        $this->execute();
    }

    /**
     * Convert case in name
     * @param  string $input The string name
     * @return string
     */
    public function convertCase($input)
    {
        if (strpos($input, '_') !== false) {
            $words  = explode('_', $input);
            $words[0] = ucfirst($words[0]);
            for ($i = 0; $i < count($words); $i++) {
                $words[$i] = ucfirst(strtolower($words[$i]));
            }
            $output = implode('_', $words);
        } else {
            $output = preg_replace('/[A-Z]/', '_$0', $input);
        }

        $output = ucfirst($output);
        return trim($output, '_');
    }

    /**
     * Execute the command
     * @return boolean TRUE on success; FALSE on failure
     */
    private function execute()
    {
        if (empty($this->name)) {
            die('Provide your migration name, e.g., php ci bare:migration add_new_post_table');
            exit;
        }

        $name       = $this->convertCase($this->name);
        $version    = date('YmdHis');
        $className  = 'Migration_'.$name;
        $fileName   = __DIR__.'/../../../../application/migrations/'.$version.'_'.strtolower($name).'.php';

        $content = <<<CODE
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class $className extends CI_Migration {

    public function up()
    {
        // Write your code here for up version
    }

    public function down()
    {
        // Write your code here for down version
    }
}
CODE;
        return file_put_contents($fileName, mb_convert_encoding($content, 'UTF-8')) ? true : false;
    }
}
