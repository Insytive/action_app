<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * MembershipNumber command.
 */
class MembershipNumberCommand extends Command
{
    protected $modelClass = 'Users';

    protected $membershipNumberPrefix = 'ASA';
    protected $membershipNumberStart;
    protected $membershipNumberYear;

    /**
     * @param mixed $membershipNumberYear
     */
    public function setMembershipNumberYear($membershipNumberYear): void {
        $this->membershipNumberYear = (int)$membershipNumberYear;
    }

    /**
     * @param mixed $membershipNumberYear
     */
    public function setMembershipNumberStart($membershipNumberStart): void {
        $this->membershipNumberStart = (int)$membershipNumberStart + 1;
    }

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
        $io->out('Membership number generator.');

        $this->setMembershipNumberYear(date('Y'));

        $io->out($this->membershipNumberYear);

        $users = $this->Users->find('all', [
            'conditions' => ['membership_number IS' => null],
            'fields' => [
                'id'
            ]
        ]);

        if (0 === $users->count()){
            $io->abort('There are no members without membership numbers.');
        }

        $this->lastMembershipNumber();

        $io->out(__('Generating membership numbers for {0} members.', $users->count()));

        $io->out(__('Starting membership number from {0}', $this->membershipNumberStart));

        foreach ($users as $user) {
            $user = $this->Users->patchEntity($user, [
                'membership_number' =>
                    $this->membershipNumberPrefix .
                    $this->membershipNumberYear .
                    $this->membershipNumberStart
            ]);

            if (!$this->Users->save($user)) {
                $io->error(print_r($user->getErrors(), true));
            }

            $this->setMembershipNumberStart($this->membershipNumberStart);
        }
    }

    private function lastMembershipNumber(){
        $numbers = $this->Users->find('all', [
            'fields' => ['membership_number'],
            'conditions' => [
                'membership_number IS NOT' => null
            ],
            'order' => ['membership_number' => 'DESC']
        ]);

        if (0 === $numbers->count()){
            return $this->setMembershipNumberStart(110000);
        }

        $last = $numbers->first();

        return $this->setMembershipNumberStart(substr($last->membership_number, 7));
    }
}
