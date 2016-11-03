<?php
/**
 * Command-line tool to generate bare CodeIgniter migration file
 *
 *    php ci migration:bare [name]
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
    /** @var array Array of commands */
    protected $commands = array(
        'migration:bare',
    );

    /**
     * Constructor
     */
    public function __construct()
    {
        global $argv;
        $this->argv = array_slice($argv, 1);
        $this->argc = count($this->argv) - 1;

        $this->command = strtolower(array_shift($this->argv));
        if ($this->validateCommand()) {
            $this->name = array_shift($this->argv);
            $this->execute();
        }
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
     * Check the command is valid
     * @return mixed TRUE or die
     */
    public function validateCommand()
    {
        if (!in_array($this->command, $this->commands)) {
            die('Command is not valid, e.g., php ci migration:bare add_new_post_table');
        }

        return true;
    }

    /**
     * Execute the command
     * @return boolean TRUE on success; FALSE on failure
     */
    private function execute()
    {
        if (empty($this->name)) {
            die('Provide your migration name, e.g., php ci migration:bare add_new_post_table');
            exit;
        }

        $name       = $this->convertCase($this->name);
        $version    = date('YmdHis');
        $className  = 'Migration_'.$name;
        $fileName   = $version.'_'.strtolower($name).'.php';
        $fullFileName = __DIR__.'/../../../../application/migrations/'.$fileName;

        $content = <<<CODE
<?php defined('BASEPATH') or exit('No direct script access allowed');

class $className extends CI_Migration
{
    public function up()
    {
        // this up() migration is auto-generated, please modify it to your needs
        // Drop table 'table_name' if it exists
        \$this->dbforge->drop_table('table_name', true);

        // Table structure for table 'table_name'
        \$this->dbforge->add_field(array(
            'id' => array(
                'type' => 'MEDIUMINT',
                'constraint' => '8',
                'unsigned' => true,
                'auto_increment' => true
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => false,
            )
        ));
        \$this->dbforge->add_key('id', true);
        \$this->dbforge->create_table('table_name');
    }

    public function down()
    {
        // this down() migration is auto-generated, please modify it to your needs
        \$this->dbforge->drop_table('table_name', true);
    }
}
CODE;
        if (file_put_contents($fullFileName, mb_convert_encoding($content, 'UTF-8'))) {
            echo 'Generated version: '.$version."\n";
            echo 'Generated file name: '.$fileName."\n";
            return true;
        } else {
            die('Generation failed.');
            return false;
        }
    }
}
