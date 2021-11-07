<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VotingStations Model
 *
 * @property \App\Model\Table\WardsTable&\Cake\ORM\Association\BelongsTo $Wards
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasMany $Users
 *
 * @method \App\Model\Entity\VotingStation newEmptyEntity()
 * @method \App\Model\Entity\VotingStation newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\VotingStation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VotingStation get($primaryKey, $options = [])
 * @method \App\Model\Entity\VotingStation findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\VotingStation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VotingStation[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\VotingStation|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VotingStation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VotingStation[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\VotingStation[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\VotingStation[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\VotingStation[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class VotingStationsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('voting_stations');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Wards', [
            'foreignKey' => 'ward_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'voting_station_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 128)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->boolean('approved')
            ->notEmptyString('approved');

        return $validator;
    }

    public function newUserStation($name){
        $votingStation = $this->find('all', [
            'conditions' => [
                'name' => $name
            ],
            'limit' => 1
        ]);

        if (1 === $votingStation->count()){
            $station = $votingStation->first();
            return $station->id;
        }

        $votingStation = $this->newEntity([
            'name' => $name,
            'approved' => false,
            'ward_id' => 1 /* @todo Consider allowing ward_id to be nullable */
        ]);

        if ($this->save($votingStation)){
            return $votingStation->id;
        }

        return null;
    }


    public function searchStationByTerm($conditions){
        return $this->find('all', [
            'fields' => [
                'VotingStations.id',
                'VotingStations.name',
                'Provinces.name',
                'Municipality.name',
                'Areas.name',
                'Wards.name',
            ],

            'conditions' => $conditions,

            'join' => [
                [
                    'table' => 'wards',
                    'alias' => 'Wards',
                    'conditions' => 'VotingStations.ward_id = Wards.id',
                ],
                [
                    'table' => 'areas',
                    'alias' => 'Areas',
                    'conditions' => 'Wards.area_id = Areas.id',
                ],
                [
                    'table' => 'municipalities',
                    'alias' => 'Municipality',
                    'conditions' => 'Areas.municipality_id = Municipality.id',
                ],
                [
                    'table' => 'provinces',
                    'alias' => 'Provinces',
                    'conditions' => 'Municipality.province_id = Provinces.id',
                ],
            ],

            'limit' => 25,
        ]);
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['ward_id'], 'Wards'), ['errorField' => 'ward_id']);

        return $rules;
    }
}
