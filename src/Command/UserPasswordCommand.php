<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * UserPassword command.
 */
class UserPasswordCommand extends Command
{
    protected $modelClass = 'Users';

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $id = $io->ask('User ID?');
        $password = $io->ask('User password?');

        if (empty($id) || empty($password)) {
            $io->error('You need to provide the user ID and a password.');
            $this->abort();
        }

        $user = $this->Users->get($id);

        $user = $this->Users->patchEntity($user, [
            'password' => $password
        ]);

        if (!$this->Users->save($user)) {
            $io->error(print_r($user->getErrors(), true));
        }

        $this->abort();
    }
}
