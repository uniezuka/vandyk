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

    public function search($page = 1, $search_text = "", $commercialOnly = false, $nonCommercialOnly = false)
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
        $builder->orderBy('build_index', 'ASC');

        $query = $builder->get();

        return $query->getResult();
    }

    private function getLatestBuildingIndex($client_id)
    {
        $buildings = $this->getBuildings($client_id);
        $building = end($buildings);

        return ($building) ? ($building->build_index + 1) : 1;
    }

    private function upsertBuildingMeta($client_building_id, $metaKey, $metaValue)
    {
        $builder = $this->db->table('building_meta');

        $builder->where(array('client_building_id' => $client_building_id, 'meta_key' => $metaKey));
        $query = $builder->get(1);

        $row = $query->getRow();

        if ($row) {
            $data = [
                'meta_value'            => $metaValue ?? "",
            ];

            $builder->set($data);
            $builder->where(array('client_building_id' => $client_building_id, 'meta_key' => $metaKey));
            $builder->update();
        } else {
            $data = [
                'client_building_id'    => $client_building_id,
                'meta_key'              => $metaKey,
                'meta_value'            => $metaValue ?? "",
            ];

            $builder->insert($data);
        }
    }

    private function getBuildingMetas($client_building_id)
    {
        $builder = $this->db->table('building_meta');

        $builder->where('client_building_id', $client_building_id);

        $query = $builder->get();

        return $query->getResult();
    }

    private function getBuildingMortgages($client_building_id)
    {
        $builder = $this->db->table('building_mortgage');

        $builder->where('client_building_id', $client_building_id);
        $builder->orderBy('loan_index ', 'ASC');

        $query = $builder->get();

        return $query->getResult();
    }

    public function addBuilding($client_id, object $message)
    {
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

        $this->upsertBuildingMeta($building_id, 'geo_place_id', $message->placeId);
        $this->upsertBuildingMeta($building_id, 'rcv_tiv', $message->replacementCost);
        $this->upsertBuildingMeta($building_id, 'personal_property_tiv', $message->personalValue);
        $this->upsertBuildingMeta($building_id, 'income_and_extra_expense_tiv', $message->incomeExpenseTotal);
        $this->upsertBuildingMeta($building_id, 'purpose', $message->purpose);
        $this->upsertBuildingMeta($building_id, 'occupancy', $message->occupancy);
        $this->upsertBuildingMeta($building_id, 'construction', $message->construction);
        $this->upsertBuildingMeta($building_id, 'floor_area', $message->floorArea);
        $this->upsertBuildingMeta($building_id, 'no_of_floors', $message->floors);
        $this->upsertBuildingMeta($building_id, 'year_built', $message->yearBuilt);
        $this->upsertBuildingMeta($building_id, 'prior_losses_3years', $message->priorLoss);
        $this->upsertBuildingMeta($building_id, 'is_elevated', $foundation->is_elevated);
        $this->upsertBuildingMeta($building_id, 'elevation_height', $message->elevationHeight);
        $this->upsertBuildingMeta($building_id, 'foundation_type', $message->foundationType);
        $this->upsertBuildingMeta($building_id, 'has_basement', $message->hasBasement);
        $this->upsertBuildingMeta($building_id, 'rate_id', '555' . $dateTime->format('YmdHis'));
        $this->upsertBuildingMeta($building_id, 'enclosure_has_elevator', $message->hasElevator);
        $this->upsertBuildingMeta($building_id, 'building_over_water', $message->overWater);
        $this->upsertBuildingMeta($building_id, 'has_below_floor_enclosure', $message->hasBelowFloorEnclosure);
        $this->upsertBuildingMeta($building_id, 'below_floor_enclosure_type', $message->enclosureType);
        $this->upsertBuildingMeta($building_id, 'below_floor_enclosure_completion_status', $message->completionStatus);
        $this->upsertBuildingMeta($building_id, 'basement_completion_status', $message->basementFinished);
        $this->upsertBuildingMeta($building_id, 'bpp_equipment_or_machinery', $message->equipmentValue);
        $this->upsertBuildingMeta($building_id, 'bpp_other', $message->otherPersonalValue);
    }

    public function removeBuilding($client_id, $building_id)
    {
        $builder = $this->db->table('building_meta');
        $builder->delete(['client_building_id ' => $building_id]);

        $builder = $this->db->table('client_building');
        $builder->delete(['client_id' => $client_id, 'client_building_id' => $building_id]);
    }

    function getBuildingMetaValue($array, $metaKey, $defaultValue = "")
    {
        $filteredData = array_filter($array, function ($row) use ($metaKey) {
            return $row->meta_key == $metaKey;
        });

        if (count($filteredData)) {
            $meta = reset($filteredData);

            return $meta->meta_value;
        }

        return $defaultValue;
    }

    public function getBuilding($id)
    {
        $builder = $this->db->table('client_building');
        $builder->where('client_building_id', $id);

        $query = $builder->get(1);

        $row = $query->getRow();

        if ($row) {
            $metas = $this->getBuildingMetas($id);

            $row->occupancy =  $this->getBuildingMetaValue($metas, "occupancy");
            $row->purpose =  $this->getBuildingMetaValue($metas, "purpose");
            $row->construction =  $this->getBuildingMetaValue($metas, "construction");
            $row->floor_area =  $this->getBuildingMetaValue($metas, "floor_area");
            $row->no_of_floors =  $this->getBuildingMetaValue($metas, "no_of_floors");
            $row->year_built =  $this->getBuildingMetaValue($metas, "year_built");
            $row->prior_losses_3years =  $this->getBuildingMetaValue($metas, "prior_losses_3years");
            $row->building_over_water =  $this->getBuildingMetaValue($metas, "building_over_water");
            $row->foundation_type =  $this->getBuildingMetaValue($metas, "foundation_type");
            $row->has_basement =  $this->getBuildingMetaValue($metas, "has_basement");
            $row->basement_completion_status =  $this->getBuildingMetaValue($metas, "basement_completion_status");
            $row->elevation_height =  $this->getBuildingMetaValue($metas, "elevation_height");
            $row->has_below_floor_enclosure =  $this->getBuildingMetaValue($metas, "has_below_floor_enclosure");
            $row->below_floor_enclosure_type =  $this->getBuildingMetaValue($metas, "below_floor_enclosure_type");
            $row->below_floor_enclosure_completion_status =  $this->getBuildingMetaValue($metas, "below_floor_enclosure_completion_status");
            $row->enclosure_has_elevator =  $this->getBuildingMetaValue($metas, "enclosure_has_elevator");
            $row->bpp_equipment_or_machinery =  $this->getBuildingMetaValue($metas, "bpp_equipment_or_machinery");
            $row->bpp_other =  $this->getBuildingMetaValue($metas, "bpp_other");
            $row->rcv_tiv =  $this->getBuildingMetaValue($metas, "rcv_tiv");
            $row->personal_property_tiv =  $this->getBuildingMetaValue($metas, "personal_property_tiv");
            $row->income_and_extra_expense_tiv =  $this->getBuildingMetaValue($metas, "income_and_extra_expense_tiv");
            $row->flood_zone =  $this->getBuildingMetaValue($metas, "flood_zone");
            $row->water_surface_elevation =  $this->getBuildingMetaValue($metas, "water_surface_elevation");
            $row->property_elevation =  $this->getBuildingMetaValue($metas, "property_elevation");
            $row->category1_water_depth =  $this->getBuildingMetaValue($metas, "category1_water_depth");
            $row->category2_water_depth =  $this->getBuildingMetaValue($metas, "category2_water_depth");
            $row->category3_water_depth =  $this->getBuildingMetaValue($metas, "category3_water_depth");
            $row->category4_water_depth =  $this->getBuildingMetaValue($metas, "category4_water_depth");
            $row->category5_water_depth =  $this->getBuildingMetaValue($metas, "category5_water_depth");

            $row->mortgages = $this->getBuildingMortgages($id);
        }

        return $row;
    }

    public function updateBuilding(object $message)
    {
        $builder = $this->db->table('client_building');

        $foundation = $this->getFoundation($message->foundationType); // TODO refer to foundationService

        $data = [
            'description'                   => $message->description,
            'address'                       => $message->address,
            'city'                          => $message->city,
            'state'                         => $message->state,
            'zip'                           => $message->zipCode,
            'county'                        => $message->county,
            'latitude'                      => $message->latitude,
            'longitude'                     => $message->longitude,
        ];

        $builder->set($data);
        $builder->where('client_building_id', $message->client_building_id);
        $builder->update();

        $this->upsertBuildingMeta($message->client_building_id, 'geo_place_id', $message->placeId);
        $this->upsertBuildingMeta($message->client_building_id, 'rcv_tiv', $message->replacementCost);
        $this->upsertBuildingMeta($message->client_building_id, 'personal_property_tiv', $message->personalValue);
        $this->upsertBuildingMeta($message->client_building_id, 'income_and_extra_expense_tiv', $message->incomeExpenseTotal);
        $this->upsertBuildingMeta($message->client_building_id, 'purpose', $message->purpose);
        $this->upsertBuildingMeta($message->client_building_id, 'occupancy', $message->occupancy);
        $this->upsertBuildingMeta($message->client_building_id, 'construction', $message->construction);
        $this->upsertBuildingMeta($message->client_building_id, 'floor_area', $message->floorArea);
        $this->upsertBuildingMeta($message->client_building_id, 'no_of_floors', $message->floors);
        $this->upsertBuildingMeta($message->client_building_id, 'year_built', $message->yearBuilt);
        $this->upsertBuildingMeta($message->client_building_id, 'prior_losses_3years', $message->priorLoss);
        $this->upsertBuildingMeta($message->client_building_id, 'is_elevated', $foundation->is_elevated);
        $this->upsertBuildingMeta($message->client_building_id, 'elevation_height', $message->elevationHeight);
        $this->upsertBuildingMeta($message->client_building_id, 'foundation_type', $message->foundationType);
        $this->upsertBuildingMeta($message->client_building_id, 'has_basement', $message->hasBasement);
        $this->upsertBuildingMeta($message->client_building_id, 'enclosure_has_elevator', $message->hasElevator);
        $this->upsertBuildingMeta($message->client_building_id, 'building_over_water', $message->overWater);
        $this->upsertBuildingMeta($message->client_building_id, 'has_below_floor_enclosure', $message->hasBelowFloorEnclosure);
        $this->upsertBuildingMeta($message->client_building_id, 'below_floor_enclosure_type', $message->enclosureType);
        $this->upsertBuildingMeta($message->client_building_id, 'below_floor_enclosure_completion_status', $message->completionStatus);
        $this->upsertBuildingMeta($message->client_building_id, 'basement_completion_status', $message->basementFinished);
        $this->upsertBuildingMeta($message->client_building_id, 'bpp_equipment_or_machinery', $message->equipmentValue);
        $this->upsertBuildingMeta($message->client_building_id, 'bpp_other', $message->otherPersonalValue);

        return $this->getBuilding($message->client_building_id);
    }

    public function updateMortgage(object $message)
    {
        $builder = $this->db->table('building_mortgage');

        $builder->where('client_building_id', $message->client_building_id);
        $builder->orderBy('loan_index ', 'ASC');

        $query = $builder->get();

        $result = $query->getResult();

        if ($result) {
            $data = [
                'loan_number'             => $message->mortgage1Loan,
                'name'                    => $message->mortgage1Name,
                'name2'                   => $message->mortgage1Name2,
                'address'                 => $message->mortgage1Address,
                'city'                    => $message->mortgage1City,
                'state'                   => $message->mortgage1State,
                'zip'                     => $message->mortgage1Zip,
                'phone'                   => $message->mortgage1Phone,
            ];

            $builder->set($data);
            $builder->where('building_mortgage_id', $result[0]->building_mortgage_id);
            $builder->update();

            $data = [
                'loan_number'             => $message->mortgage2Loan,
                'name'                    => $message->mortgage2Name,
                'name2'                   => $message->mortgage2Name2,
                'address'                 => $message->mortgage2Address,
                'city'                    => $message->mortgage2City,
                'state'                   => $message->mortgage2State,
                'zip'                     => $message->mortgage2Zip,
                'phone'                   => $message->mortgage2Phone,
            ];

            $builder->set($data);
            $builder->where('building_mortgage_id', $result[1]->building_mortgage_id);
            $builder->update();
        } else {
            $data = [
                'client_building_id'      => $message->client_building_id,
                'loan_index'              => 1,
                'loan_number'             => $message->mortgage1Loan,
                'name'                    => $message->mortgage1Name,
                'name2'                   => $message->mortgage1Name2,
                'address'                 => $message->mortgage1Address,
                'city'                    => $message->mortgage1City,
                'state'                   => $message->mortgage1State,
                'zip'                     => $message->mortgage1Zip,
                'phone'                   => $message->mortgage1Phone,
            ];

            $builder->insert($data);

            $data = [
                'client_building_id'      => $message->client_building_id,
                'loan_index'              => 2,
                'loan_number'             => $message->mortgage2Loan,
                'name'                    => $message->mortgage2Name,
                'name2'                   => $message->mortgage2Name2,
                'address'                 => $message->mortgage2Address,
                'city'                    => $message->mortgage2City,
                'state'                   => $message->mortgage2State,
                'zip'                     => $message->mortgage2Zip,
                'phone'                   => $message->mortgage2Phone,
            ];

            $builder->insert($data);
        }
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
