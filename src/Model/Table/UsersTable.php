<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\Mailer\MailerAwareTrait;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property UsersTable&BelongsTo          $ParentUsers
 * @property GendersTable&BelongsTo        $Genders
 * @property VotingStationsTable&BelongsTo $VotingStations
 * @property BranchesTable&BelongsTo       $Branches
 * @property RolesTable&BelongsTo          $Roles
 * @property ProvincesTable&BelongsTo      $Provinces
 * @property AuditLogsTable&HasMany        $AuditLogs
 * @property SessionsTable&HasMany         $Sessions
 * @property UsersTable&HasMany            $ChildUsers
 * @property BadgesTable&BelongsToMany     $Badges
 *
 * @method User newEmptyEntity()
 * @method User newEntity(array $data, array $options = [])
 * @method User[] newEntities(array $data, array $options = [])
 * @method User get($primaryKey, $options = [])
 * @method User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method User patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method User|false save(EntityInterface $entity, $options = [])
 * @method User saveOrFail(EntityInterface $entity, $options = [])
 * @method User[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method User[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method User[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method User[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class UsersTable extends Table
{
    use MailerAwareTrait;

    public $idParts = [];

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('ParentUsers', [
            'className' => 'Users',
            'foreignKey' => 'parent_id',
        ]);
        $this->belongsTo('Genders', [
            'foreignKey' => 'gender_id',
        ]);
        $this->belongsTo('VotingStations', [
            'foreignKey' => 'voting_station_id',
        ]);
        $this->belongsTo('Branches', [
            'foreignKey' => 'branch_id',
        ]);
        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
        ]);
        $this->belongsTo('Provinces', [
            'foreignKey' => 'province_id',
        ]);
        $this->hasMany('AuditLogs', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Sessions', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('ChildUsers', [
            'className' => 'Users',
            'foreignKey' => 'parent_id',
        ]);
        $this->belongsToMany('Badges', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'badge_id',
            'joinTable' => 'badges_users',
        ]);


        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_at' => 'new',
                    'updated_at' => 'always',
                ],
            ]
        ]);

        // Attach events
        $this->getEventManager()->on($this->getMailer('User'));
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     *
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('first_name')
            ->maxLength('first_name', 128)
            ->requirePresence('first_name', 'create')
            ->notEmptyString('first_name');

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 128)
            ->requirePresence('last_name', 'create')
            ->notEmptyString('last_name');

        $validator
            ->scalar('id_number')
            ->maxLength('id_number', 256)
            ->requirePresence('id_number', 'create')
            ->notEmptyString('id_number')
            ->add('id_number', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'This ID Number already exists.'])
            ->add('id_number', 'custom', ['rule' => 'validateIdNumber', 'provider' => 'table', 'message'=>'Invalid South African ID Number']);

        $validator
            ->date('birthdate')
            ->allowEmptyDate('birthdate');

        $validator
            ->scalar('membership_number')
            ->maxLength('membership_number', 16)
            ->allowEmptyString('membership_number');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('password')
            ->maxLength('password', 256)
            ->allowEmptyString('password')
            ->add('password', 'custom', ['rule' => 'validatePasswordStrength', 'provider' => 'table', 'message'=>'Password must be at least 8 characters long and contain at least 1 number and a special character.']);

        $validator
            ->scalar('phone')
            ->maxLength('phone', 32)
            ->notEmptyString('phone');

        $validator
            ->scalar('street_address')
            ->maxLength('street_address', 256)
            ->allowEmptyString('street_address');

        $validator
            ->scalar('suburb')
            ->maxLength('suburb', 64)
            ->allowEmptyString('suburb');

        $validator
            ->scalar('town')
            ->maxLength('town', 64)
            ->allowEmptyString('town');

        $validator
            ->scalar('municipality')
            ->maxLength('municipality', 128)
            ->allowEmptyString('municipality');

        $validator
            ->integer('postal_address')
            ->allowEmptyString('postal_address');

        $validator
            ->boolean('first_time_voter')
            ->notEmptyString('first_time_voter');

        $validator
            ->integer('user_status')
            ->notEmptyString('user_status');

        $validator
            ->scalar('token')
            ->maxLength('token', 8)
            ->allowEmptyString('token');

        $validator
            ->scalar('password_reset')
            ->maxLength('password_reset', 32)
            ->allowEmptyString('password_reset');

        $validator
            ->integer('points')
            ->notEmptyString('points');

        $validator
            ->dateTime('password_expiry')
            ->allowEmptyDateTime('password_expiry');

        $validator
            ->dateTime('created_at')
            ->allowEmptyDateTime('created_at');

        $validator
            ->dateTime('updated_at')
            ->allowEmptyDateTime('updated_at');

        return $validator;
    }

    public function validationDisablePasswordCheck(Validator $validator) {
        $validator = $this->validationDefault($validator);
        $validator->remove('password', 'custom');
        return $validator;
    }

    /**
     * @param array $idParts
     */
    public function setIdParts(array $idParts): void {
        $this->idParts = $idParts;
    }

    public function validateIdNumber($id_number){
        if (strlen($id_number) !== 13 || !is_numeric($id_number) ) {
            return false;
        }

        $year = substr($id_number, 0,2);
        $currentYear = date("Y") % 100;

        $prefix = '19';

        if ($year < $currentYear){
            $prefix = '20';
        }

        $id_year = $prefix . $year;

        $id_month = substr($id_number, 2,2);
        $id_date = substr($id_number, 4,2);

        $fullDate = $id_year .'-'. $id_month .'-'. $id_date ;


        if ( !$id_year  == substr($id_number, 0,2) &&
              $id_month == substr($id_number, 2,2) &&
              $id_date  == substr($id_number, 4,2))
        {
            return false;
        }

        $genderCode = substr($id_number, 6,4);
        $gender_id = (int)$genderCode < 5000 ? 2 : 1;

        $citzenship = (int)substr($id_number, 10,1)  === 0 ? 'Citizen' : 'Resident'; //0 for South African citizen, 1 for a permanent resident

        $total = 0;
        $count = 0;

        for ($i = 0; $i < strlen($id_number); ++$i)
        {
            $multiplier = $count % 2 + 1;
            $count ++;
            $temp = $multiplier * (int)$id_number[$i];
            $temp = floor($temp / 10) + ($temp % 10);
            $total += $temp;
        }

        $total = ($total * 9) % 10;

        if ($total % 10 != 0) {
            return false;
        }

        $this->setIdParts([
            'id_number'   => $id_number,
            'birthdate'   => $fullDate,
            'gender_id'   => $gender_id,
            'citizenship' => $citzenship,
        ]);

        return true;
    }


    public function generateMembershipNumber(){
        $numbers = $this->find('all', [
            'fields' => ['membership_number'],
            'conditions' => [
                'membership_number IS NOT' => null,
                'membership_number LIKE ' => MEMBER_NUMBER_PREFIX . '%'
            ],
            'order' => ['membership_number' => 'DESC']
        ]);

        if (0 === $numbers->count()){
            return 110000+1;
        }

        $last = $numbers->first();

        $number = substr($last->membership_number, 7) + 1;

        return MEMBER_NUMBER_PREFIX . date('Y') . $number;
    }


    public function generateMembershipNumberFromId(EntityInterface $user){
        // Start number for Membership Numbers is One Hundred and Ten Thousand
        // Add the user ID to the start number
        // Subtract 50 (Allow ourselves some leg room from date of dev until deployment of this feature)

        $number = 110000 + $user->id - 50;

        return MEMBER_NUMBER_PREFIX . date('Y') . $number;
    }

    public function updateMembershipNumber(EntityInterface $user){
        $number = $this->generateMembershipNumberFromId($user);

        $connection = ConnectionManager::get('default');

        $connection->execute(
            'UPDATE users SET membership_number = ? WHERE id = ?',
            [$number, $user->id]
        );

    }


    public function userIdByToken($token){
        $parent = $this->find('all', [
            'fields' => ['Users.id'],
            'conditions' => ['Users.token' => $token]
        ]);

        if (1 === $parent->count()){
            $user = $parent->first();
            return $user->id;
        }

        return null;
    }

    public function canAccess($id, $parent_id){
        if ( $id == $parent_id){
            return true;
        }

        $permission = $this->find('all', [
            'fields' => ['Users.id'],
            'conditions' => [
                'Users.id' => $id,
                'Users.parent_id' => $parent_id,
            ],
            'limit' => 1
        ]);

        return (1 === $permission->count());
    }

    /**
     * @param array $conditions
     * @return Query
     */
    public function membersByProvince($conditions){
        $query = $this->find();

        return $query
            ->select(['Provinces.id', 'Provinces.name', 'total' => $query->func()->count('Provinces.id')])
            ->join([
                [
                    'table' => 'voting_stations',
                    'alias' => 'VotingStations',
                    'type' => 'INNER',
                    'conditions' => 'Users.voting_station_id=VotingStations.id'
                ],
                [
                    'table' => 'wards',
                    'alias' => 'Wards',
                    'type' => 'INNER',
                    'conditions' => 'VotingStations.ward_id=Wards.id'
                ],
                [
                    'table' => 'areas',
                    'alias' => 'Areas',
                    'type' => 'INNER',
                    'conditions' => 'Wards.area_id=Areas.id'
                ],
                [
                    'table' => 'municipalities',
                    'alias' => 'Municipalities',
                    'type' => 'INNER',
                    'conditions' => 'Municipalities.id=Areas.municipality_id'
                ],
                [
                    'table' => 'provinces',
                    'alias' => 'Provinces',
                    'type' => 'INNER',
                    'conditions' => 'Municipalities.province_id=Provinces.id'
                ]
            ])
            ->where($conditions)
            ->group(['Provinces.id'])
            ->order(['total DESC']);
    }


    public function filteredUsers(array $conditions = []){
        return $this->find('all', [
            'fields' => [
                'Users.id',
                'Users.created_at',
                'Users.membership_number',
                'Users.first_name',
                'Users.last_name',
                'Users.first_time_voter',
                'Users.phone',
                'Users.email',
                'Users.suburb',
                'Users.town',

                'Roles.name',

                'ParentUsers.first_name',
                'ParentUsers.last_name',

                'Users.token',

                'Branches.name',
                'VotingStations.name',
                'Wards.name',
                'Areas.name',
                'Municipalities.name',
                'Provinces.name',
            ],

            'contain' => ['ParentUsers', 'Branches', 'Roles'],

            'join' => [[
                'table' => 'voting_stations',
                'alias' => 'VotingStations',
                'type'  => 'LEFT',
                'conditions' => 'VotingStations.id = Users.voting_station_id',
            ], [
                'table' => 'wards',
                'alias' => 'Wards',
                'type'  => 'LEFT',
                'conditions' => 'VotingStations.ward_id = Wards.id',
            ], [
                'table' => 'areas',
                'alias' => 'Areas',
                'type'  => 'LEFT',
                'conditions' => 'Wards.area_id = Areas.id',
            ], [
                'table' => 'municipalities',
                'alias' => 'Municipalities',
                'type'  => 'LEFT',
                'conditions' => 'Areas.municipality_id = Municipalities.id',
            ], [
                'table' => 'provinces',
                'alias' => 'Provinces',
                'type'  => 'LEFT',
                'conditions' => 'Municipalities.province_id = Provinces.id',
            ]],

            'conditions' => $conditions
        ]);
    }


    public function validatePasswordStrength($field){
        //$uppercase = preg_match('@[A-Z]@', $field);
        $lowercase = preg_match('@[a-z]@', $field);
        $number    = preg_match('@[0-9]@', $field);
        $specialChars = preg_match('@[^\w]@', $field);

        if( !$lowercase || !$number || !$specialChars || strlen($field) < 8) {
            return false;
        }

        return true;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param RulesChecker $rules The rules object to be modified.
     *
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        //$rules->add($rules->isUnique(['phone']), ['errorField' => 'phone']);
        $rules->add($rules->isUnique(['id_number']), ['errorField' => 'id_number']);
        $rules->add($rules->existsIn(['parent_id'], 'ParentUsers'), ['errorField' => 'parent_id']);
        $rules->add($rules->existsIn(['gender_id'], 'Genders'), ['errorField' => 'gender_id']);
        $rules->add($rules->existsIn(['voting_station_id'], 'VotingStations'), ['errorField' => 'voting_station_id']);
        $rules->add($rules->existsIn(['branch_id'], 'Branches'), ['errorField' => 'branch_id']);
        $rules->add($rules->existsIn(['role_id'], 'Roles'), ['errorField' => 'role_id']);
        $rules->add($rules->existsIn(['province_id'], 'Provinces'), ['errorField' => 'province']);

        return $rules;
    }

}
