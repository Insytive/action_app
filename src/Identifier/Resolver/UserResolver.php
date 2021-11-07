<?php namespace App\Identifier\Resolver;

use Authentication\Identifier\Resolver\ResolverInterface;
use Cake\Core\InstanceConfigTrait;
use Cake\ORM\Locator\LocatorAwareTrait;

class UserResolver implements ResolverInterface
{
    use InstanceConfigTrait;
    use LocatorAwareTrait;

    /**
     * @inheritDoc
     */
    public function find(array $conditions, $type = self::TYE_AND) {
        $type = self::TYPE_AND;

        $table = $this->getTableLocator()->get('Users');

        $query = $table->find('all');

        $query->select([
            'id', 'first_name', 'last_name', 'email', 'password', 'role_id', 'user_status'
        ]);

        $where = [
            'role_id IN' => [9],
            'user_status &' => 1,
        ];

        foreach ($conditions as $field => $value) {
            $field = $table->aliasField($field);
            if (is_array($value)) {
                $field = $field . ' IN';
            }
            $where[$field] = $value;
        }

        return $query->where([$type => $where])->first();
    }
}
