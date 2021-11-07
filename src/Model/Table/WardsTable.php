<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Wards Model
 *
 * @property \App\Model\Table\AreasTable&\Cake\ORM\Association\BelongsTo $Areas
 * @property \App\Model\Table\VotingStationsTable&\Cake\ORM\Association\HasMany $VotingStations
 *
 * @method \App\Model\Entity\Ward newEmptyEntity()
 * @method \App\Model\Entity\Ward newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Ward[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Ward get($primaryKey, $options = [])
 * @method \App\Model\Entity\Ward findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Ward patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Ward[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Ward|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Ward saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Ward[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Ward[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Ward[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Ward[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class WardsTable extends Table
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

        $this->setTable('wards');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Areas', [
            'foreignKey' => 'area_id',
            'joinType' => 'INNER',
        ]);

        $this->hasMany('VotingStations', [
            'foreignKey' => 'ward_id',
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
            ->maxLength('name', 64)
            ->notEmptyString('name');

        return $validator;
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
        $rules->add($rules->existsIn(['area_id'], 'Areas'), ['errorField' => 'area_id']);

        return $rules;
    }

    public function setSqlStatement($conditions = []): array {
        return [
            'fields' => [
                'Wards.id',
                'Wards.name',
                'Wards.area_id',

                'Areas.id',
                'Areas.name',

                'Municipality.name',

                'Provinces.code',
            ],

            'join' => [
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

            'conditions' => $conditions,
        ];
    }
}
