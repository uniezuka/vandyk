<?php

namespace App\Services;

class HiscoxQuoteService extends BaseService
{
    protected $limit = 20;

    public function upsert($message)
    {
        $builder = $this->db->table('hiscox_quote');

        $builder->where(array('client_id' => $message->client_id, 'flood_quote_id' => $message->flood_quote_id));
        $query = $builder->get(1);

        $row = $query->getRow();

        if ($row) {
            $data = [
                'hiscoxID'                  => $message->hiscoxID,
                'quote_expiration_date'     => $message->quoteExpirationDate ?? "",
                'quote_requested_date'      => $message->quoteRequestedDate ?? "",
                'selected_policy_type'      => $message->selectedPolicyType ?? -1,
                'selected_deductible'       => $message->selectedDeductible ?? -1,
                'selected_policy_index'     => $message->selectedPolicyIndex ?? -1,
                'raw_quotes'                => $message->rawQuotes ?? "",
            ];
            $builder->set($data);
            $builder->where(array('client_id' => $message->client_id, 'flood_quote_id' => $message->flood_quote_id));
            $builder->update();
        } else {
            $data = [
                'hiscoxID'                  => $message->hiscoxID,
                'flood_quote_id'            => $message->flood_quote_id,
                'client_id'                 => $message->client_id,
                'quote_expiration_date'     => $message->quoteExpirationDate ?? "",
                'quote_requested_date'      => $message->quoteRequestedDate ?? "",
                'selected_policy_type'      => $message->selectedPolicyType ?? -1,
                'selected_deductible'       => $message->selectedDeductible ?? -1,
                'selected_policy_index'     => $message->selectedPolicyIndex ?? -1,
                'raw_quotes'                => $message->rawQuotes ?? "",
            ];

            $builder->insert($data);
        }
    }

    private function updateFloodQuoteMeta($flood_quote_id, $meta_key, $meta_value)
    {
        $sql = "INSERT INTO fq_flood_quote_meta (flood_quote_id, meta_key, meta_value) 
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE meta_value = VALUES(meta_value)";
        $this->db->query($sql, [$flood_quote_id, $meta_key, $meta_value]);
    }

    public function addHiscoxId($flood_quote_id, $hiscoxID)
    {
        $this->updateFloodQuoteMeta($flood_quote_id, "hiscoxID", $hiscoxID);
    }

    public function updateSelectedHiscoxQuote($message)
    {
        $this->updateFloodQuoteMeta($message->flood_quote_id, "selectedPolicyType", $message->selectedPolicyType);
        $this->updateFloodQuoteMeta($message->flood_quote_id, "selectedDeductible", $message->selectedDeductible);
        $this->updateFloodQuoteMeta($message->flood_quote_id, "selectedPolicyIndex", $message->selectedPolicyIndex);
    }

    public function updateQuoteWithHiscox($floodQuoteId, $hiscox)
    {
        $this->updateFloodQuoteMeta($floodQuoteId, "hiscoxQuotedPremium", $hiscox->totalPremium);
        $this->updateFloodQuoteMeta($floodQuoteId, "hiscoxQuotedDwellCov", $hiscox->coverageLimits->building);
        $this->updateFloodQuoteMeta($floodQuoteId, "hiscoxQuotedPersPropCov", $hiscox->coverageLimits->contents);
        $this->updateFloodQuoteMeta($floodQuoteId, "hiscoxQuotedOtherCov", $hiscox->coverageLimits->otherStructures);
        $this->updateFloodQuoteMeta($floodQuoteId, "hiscoxQuotedLossCov", $hiscox->coverageLimits->lossOfUse);
        $this->updateFloodQuoteMeta($floodQuoteId, "hiscoxQuotedDeductible", $hiscox->deductible);

        $this->updateFloodQuoteMeta($floodQuoteId, "selectedPolicyType", $hiscox->selectedPolicyType);
        $this->updateFloodQuoteMeta($floodQuoteId, "selectedDeductible", $hiscox->selectedDeductible);
        $this->updateFloodQuoteMeta($floodQuoteId, "selectedPolicyIndex", $hiscox->selectedPolicyIndex);
    }

    public function findHiscoxQuote($flood_quote_id, $hiscoxID)
    {
        $builder = $this->db->table('hiscox_quote');
        $builder->where(array('flood_quote_id' => $flood_quote_id, 'hiscoxID' => $hiscoxID));

        $query = $builder->get(1);

        $row = $query->getRow();

        return $row;
    }

    public function bindQuoteWithHiscox($flood_quote_id, $hiscox)
    {
        $this->updateFloodQuoteMeta($flood_quote_id, "hiscoxBoundDate", $hiscox->boundDate);
        $this->updateFloodQuoteMeta($flood_quote_id, "hiscoxBoundReference", $hiscox->boundReference);
        $this->updateFloodQuoteMeta($flood_quote_id, "boundHiscoxID", $hiscox->boundHiscoxID);
    }

    public function bindCancelHiscox($flood_quote_id, $hiscox)
    {
        $this->updateFloodQuoteMeta($flood_quote_id, "hiscoxID", "");
        $this->updateFloodQuoteMeta($flood_quote_id, "hiscoxBoundDate", $hiscox->boundDate);
        $this->updateFloodQuoteMeta($flood_quote_id, "isBounded", 1);
        $this->updateFloodQuoteMeta($flood_quote_id, "boundHiscoxID", $hiscox->boundHiscoxID);
        $this->updateFloodQuoteMeta($flood_quote_id, "hiscoxIssuedDate", $hiscox->hiscoxIssuedDate);
        $this->updateFloodQuoteMeta($flood_quote_id, "hiscoxBoundReference", $hiscox->boundReference);
    }

    public function reinstate($flood_quote_id, $reinstatementDate, $hiscox_id) 
    {
        $builder = $this->db->table('flood_quote');
        $query = $builder->getWhere(['flood_quote_id' => $flood_quote_id]);
        $row = $query->getRowArray();

        $mortgages = $this->getMortgages($flood_quote_id);

        if ($row) {
            unset($row['flood_quote_id']);
            $builder->insert($row);
            $newId = $this->db->insertID();

            $floodQuoteMetas = (array) $this->getFloodQuoteMetas($flood_quote_id);
            $floodQuoteMetas['policyType'] = "REI";
            $floodQuoteMetas['hiscoxID'] = $hiscox_id;
            $floodQuoteMetas['hiscoxReinstatementDate'] = $reinstatementDate;
            
            $this->upsertMetaValues($floodQuoteMetas, $newId);

            foreach ($mortgages as $mortgage) {
                $mortgageData = (array) $mortgage;
                unset($mortgageData['flood_quote_mortgage_id']);
                $mortgageData['flood_quote_id'] = $newId;

                $mortgageBuilder = $this->db->table('flood_quote_mortgage');
                $mortgageBuilder->insert($mortgageData);
            }
        } else {
            throw new Exception("Unable to reinstate quote!");
        }
    }

    private function getMortgages($flood_quote_id)
    {
        $builder = $this->db->table('flood_quote_mortgage');

        $builder->where('flood_quote_id', $flood_quote_id);

        $query = $builder->get();

        return $query->getResult();
    }

    private function getFloodQuoteMetas($flood_quote_id)
    {
        $builder = $this->db->table('flood_quote_meta');

        $builder->where('flood_quote_id', $flood_quote_id);

        $query = $builder->get();

        $resultArray = [];

        foreach ($query->getResult() as $row) {
            $resultArray[$row->meta_key] = $row->meta_value;
        }

        return $resultArray;
    }

    private function upsertMetaValues($message, $flood_quote_id)
    {
        foreach ($message as $key => $value) {
            $sql = "INSERT INTO fq_flood_quote_meta (flood_quote_id, meta_key, meta_value) 
                    VALUES (?, ?, ?)
                    ON DUPLICATE KEY UPDATE meta_value = VALUES(meta_value)";
            $this->db->query($sql, [$flood_quote_id, $key, $value]);
        }
    }
}
