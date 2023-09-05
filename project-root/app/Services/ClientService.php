<?php

namespace App\Services;

use DateTime;

class ClientService extends BaseService
{
    protected $limit = 20;

    public function getPaged($page = 1, $commercialOnly = false, $nonCommercialOnly = false)
    {
        $offset = ($page - 1) * $this->limit;

        $builder = $this->db->table('client');
        
        if ($commercialOnly)
            $query = $builder->getWhere(['is_commercial' => 1], $this->limit, $offset, false);
        else if ($nonCommercialOnly)
            $query = $builder->getWhere(['is_commercial' => 0], $this->limit, $offset, false);
        else
            $query = $builder->get($this->limit, $offset, false);

        $total = $builder->countAllResults(false);

        return (object) array(
            'data'   => $query->getResult(),
            'total'  => $total,
            'page'   => $page,
            'limit'  => $this->limit,
            'offset' => $offset,
        );
    }

    public function search($page = 1, $search_text, $commercialOnly = false, $nonCommercialOnly = false)
    {
        $offset = ($page - 1) * $this->limit;

        $builder = $this->db->table('client');

        $builder->groupStart();
        $builder->like('last_name', $search_text, 'both', null, true);
        $builder->orLike('first_name', $search_text, 'both', null, true);
        $builder->orLike('client_code', $search_text, 'both', null, true);
        $builder->orLike('address', $search_text, 'both', null, true);
        $builder->orLike('business_name', $search_text, 'both', null, true);
        $builder->orLike('business_name2', $search_text, 'both', null, true);
        $builder->groupEnd();

        if ($commercialOnly)
            $builder->where('is_commercial', 1);
        else if ($nonCommercialOnly)
            $builder->where('is_commercial', 0);

        $total = $builder->countAllResults(false);

        $query = $builder->get($this->limit, $offset, false);

        return (object) array(
            'data'   => $query->getResult(),
            'total'  => $total,
            'page'   => $page,
            'limit'  => $this->limit,
            'offset' => $offset,
        );
    }

    public function create(object $message)
    {
        $builder = $this->db->table('client');

        $data = [
            'first_name'                  => $message->firstName,
            'last_name'                   => $message->lastName,
            'insured2_name'               => $message->clientName2,
            'business_name'               => $message->companyName,
            'business_name2'              => $message->companyName2,
            'address'                     => $message->address,
            'city'                        => $message->city,
            'state'                       => $message->state,
            'zip'                         => $message->zip,
            'cell_phone'                  => $message->cellPhone,
            'home_phone'                  => $message->homePhone,
            'email'                       => $message->email,
            'client_code'                 => $message->clientCode,
            'broker_id'                   => $message->brokerId,
            'tag_code'                    => '0',
            'entity_type'                 => $message->entityType,
            'is_commercial'               => ($message->isCommercial === 'true'),
            'business_as'                 => $message->businessAs,
            'business_entity_type_id'     => $message->businessEntityTypeId,
            'date_entered'                => date("Y-m-d H:i:s"),
        ];

        $builder->insert($data);

        $id = $this->db->insertID();

        return $this->findOne($id);
    }

    public function findOne($id)
    {
        $builder = $this->db->table('client');
        $builder->where('client_id', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }

    public function update(object $message)
    {
        $builder = $this->db->table('client');

        $data = [
            'first_name'                  => $message->firstName,
            'last_name'                   => $message->lastName,
            'insured2_name'               => $message->clientName2,
            'business_name'               => $message->companyName,
            'business_name2'              => $message->companyName2,
            'address'                     => $message->address,
            'city'                        => $message->city,
            'state'                       => $message->state,
            'zip'                         => $message->zip,
            'cell_phone'                  => $message->cellPhone,
            'home_phone'                  => $message->homePhone,
            'email'                       => $message->email,
            'client_code'                 => $message->clientCode,
            'broker_id'                   => $message->brokerId,
            'tag_code'                    => '0',
            'entity_type'                 => $message->entityType,
            'is_commercial'               => ($message->isCommercial === 'true'),
            'business_as'                 => $message->businessAs,
            'business_entity_type_id'     => $message->businessEntityTypeId,
        ];

        $builder->set($data);
        $builder->where('client_id', $message->client_id);
        $builder->update();
        
        return $this->findOne($message->client_id);
    }

    public function getBuildings($client_id)
    {
        $builder = $this->db->table('client_building');
        $builder->where('client_id', $client_id);
        $builder->orderBy('build_index ', 'ASC');

        $query = $builder->get();

        return $query->getResult();
    }

    private function getLatestBuildingIndex($client_id) {
        $buildings = $this->getBuildings($client_id);
        $building = end($buildings);

        return ($building) ? ($building->build_index + 1) : 1;
    }

    private function createBuildingMeta($client_building_id, $metaKey, $metaValue) {
        $builder = $this->db->table('building_meta');

        $data = [
            'client_building_id'            => $client_building_id,
            'meta_key'                      => $metaKey,
            'meta_value'                    => $metaValue ?? "",
        ];

        $builder->insert($data);
    }

    public function addBuilding($client_id, object $message) {
        $builder = $this->db->table('client_building');
        $buildIndex = $this->getLatestBuildingIndex($client_id);
        $address = $message->streetNumber . " " . $message->street;
        $foundation = $this->getFoundation($message->foundationType); // TODO refer to foundationService
        $dateTime = new DateTime();

        $data = [
            'client_id'                     => $client_id,
            'build_index'                   => $buildIndex,
            'description'                   => $message->description,
            'address'                       => $address,
            'city'                          => $message->city,
            'state'                         => $message->state,
            'zip'                           => $message->zipCode,
            'county'                        => $message->county,
            'latitude'                      => $message->latitude,
            'longitude'                     => $message->longitude,
        ];

        $builder->insert($data);

        $building_id = $this->db->insertID();

        $this->createBuildingMeta($building_id, 'geo_place_id', $message->placeId);
        $this->createBuildingMeta($building_id, 'rcv_tiv', $message->replacementCost);
        $this->createBuildingMeta($building_id, 'personal_property_tiv', $message->personalValue);
        $this->createBuildingMeta($building_id, 'income_and_extra_expense_tiv', $message->incomeExpenseTotal);
        $this->createBuildingMeta($building_id, 'purpose', $message->purpose);
        $this->createBuildingMeta($building_id, 'occupancy', $message->occupancy);
        $this->createBuildingMeta($building_id, 'construction', $message->construction);
        $this->createBuildingMeta($building_id, 'floor_area', $message->floorArea);
        $this->createBuildingMeta($building_id, 'no_of_floors', $message->floors);
        $this->createBuildingMeta($building_id, 'year_built', $message->yearBuilt);
        $this->createBuildingMeta($building_id, 'prior_losses_3years', $message->priorLoss);
        $this->createBuildingMeta($building_id, 'is_elevated', $foundation->is_elevated);
        $this->createBuildingMeta($building_id, 'elevation_height', $message->elevationHeight);
        $this->createBuildingMeta($building_id, 'foundation_type', $message->foundationType);
        $this->createBuildingMeta($building_id, 'has_basement', $message->hasBasement);
        $this->createBuildingMeta($building_id, 'rate_id', '555' . $dateTime->format('YmdHis'));
        $this->createBuildingMeta($building_id, 'enclosure_has_elevator', $message->hasElevator);
        $this->createBuildingMeta($building_id, 'building_over_water', $message->overWater);
        $this->createBuildingMeta($building_id, 'has_below_floor_enclosure', $message->hasBelowFloorEnclosure);
        $this->createBuildingMeta($building_id, 'below_floor_enclosure_type', $message->enclosureType);
        $this->createBuildingMeta($building_id, 'below_floor_enclosure_completion_status', $message->completionStatus);
        $this->createBuildingMeta($building_id, 'basement_completion_status', $message->basementFinished);
        $this->createBuildingMeta($building_id, 'bpp_equipment_or_machinery', $message->equipmentValue);
        $this->createBuildingMeta($building_id, 'bpp_other', $message->otherPersonalValue);
        $this->createBuildingMeta($building_id, 'bpp_other', $message->otherPersonalValue);
    }

    public function removeBuilding($client_id, $building_id) {
        $builder = $this->db->table('building_meta');
        $builder->delete(['client_building_id ' => $building_id]);

        $builder = $this->db->table('client_building');
        $builder->delete(['client_id' => $client_id, 'client_building_id' => $building_id]);
    }

    // TODO refer to foundationService
    private function getFoundation($id)
    {
        $builder = $this->db->table('foundation');
        $builder->where('foundation_id', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }
}
