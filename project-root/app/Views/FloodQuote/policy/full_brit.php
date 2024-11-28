<?= $this->extend('layouts/print', ['data' => $data]) ?>
<?= $this->section('content') ?>

<?php
$formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
helper(['html', 'umr']);
extract($data);

$bindAuthorityService = service('bindAuthorityService');

$entityType = $floodQuote->entity_type;
$covCContent = (int)getMetaValue($floodQuoteMetas, "covCContent", 0);
$covDLossUse = (float)getMetaValue($floodQuoteMetas, "covDLossUse", 0);
$bind_authority = (int)getMetaValue($floodQuoteMetas, 'bind_authority', 0);
$policyNumber = getMetaValue($floodQuoteMetas, "policyNumber");
$boundDate = (getMetaValue($floodQuoteMetas, "boundDate") == "") ? "" : date('m/d/Y', strtotime(getMetaValue($floodQuoteMetas, "boundDate")));
$boundLossUseCoverage = (float)getMetaValue($floodQuoteMetas, "boundLossUseCoverage", 0);

$bindAuthority = $bindAuthorityService->findOne($bind_authority);
$bindAuthorityText = ($bindAuthority) ? $bindAuthority->reference : "";

function getMetaValue($metas, $meta_key, $default = '')
{
    foreach ($metas as $meta) {
        if ($meta->meta_key === $meta_key) {
            if ($meta->meta_value == "" && $default != "")
                return $default;
            else
                return $meta->meta_value;
        }
    }
    return $default;
}
?>

<style>
    .signature-date {
        display: flex;
        align-items: flex-end;
        height: 100%;
    }

    .date-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-left: 20px;
    }

    .row.align-items-center {
        display: flex;
        align-items: center;
    }

    .bottom-row {
        font-size: 11px;
    }

    @media print {

        body,
        html {
            height: 100%;
        }

        table>thead>tr>th,
        table>tbody>tr>td {
            padding: 5px;
            font-size: 12px;
        }

        .content-wrapper {
            display: flex;
            flex-direction: column;
            height: 100vh;
            box-sizing: border-box;
        }

        .broker-info {
            font-size: 10px;
        }

        .form-info {
            font-size: 10px;
        }

        h3 {
            font-size: 22px;
        }

        .bottom-row {
            margin-top: auto;
        }

        hr {
            border: 1px solid #000;
            margin: 1rem 0;
        }
    }
</style>

<!-- Page 1 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>CONFORMITY CLAUSE</strong></p>
            <p>It is understood and agreed that the following terms shall be synonymous, wherever used in this Certificate:</p>

            <p>&ldquo;Policy&ldquo; and &ldquo;Certificate&rdquo;<br />
                &ldquo;Policy Period&rdquo;, &ldquo;Period of Insurance&rdquo; and &ldquo;Certificate Period&rdquo;<br />
                &ldquo;Insurer&rdquo; and &ldquo;Underwriters&rdquo;<br />
                &ldquo;You&rdquo;, &ldquo;your&rdquo;, &ldquo;Insured&rdquo; and &ldquo;Named Assured&rdquo;</p>

            <p>&nbsp;</p>
            <p class="text-center"><strong>ALL OTHER TERMS AND CONDITIONS OF THIS POLICY REMAIN UNCHANGED</strong></p>
            <p>LII 166 (03/08)</p>
        </div>
    </div>
</div>

<!-- Page 2 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>LIBERALISATION CLAUSE</strong></p>
            <p>If the NFIP makes a change that broadens your coverage under this edition of our <strong>policy</strong>, but does not require any additional premium, then that change will automatically apply to your insurance as of the date the NFIP underwriters implement the change, provided that this implementation date falls within 60 days before, or during the <strong>policy </strong>term stated on the <strong>Declarations Page</strong>. <br />
                All other terms and conditions remain unaltered </p>
            <p>Where the policy limits exceed NFIP maximum available limits, this policy is not intended to comply or conform to NFIP standards and the provisions within stand as written.</p>
            <p>LIBUSV12020e001 </p>
        </div>
    </div>
</div>

<!-- Page 3 -->
<div class="content-wrapper">
    <div class="row align-items-center border-top border-bottom border-dark py-4">
        <div class="col-auto">
            <img src="<?= base_url('assets/images/LloydsCertLogo.jpg'); ?>" alt="Lloyd's Logo" width="100" height="100">
        </div>
        <div class="col">
            <h1 class="text-right">Lloyd's<br /> Certificate</h1>
        </div>
    </div>

    <div class="row border-bottom border-dark py-4">
        <blockquote>
            <blockquote>
                <p><strong>This Insurance</strong> is effected with certain Underwriters at Lloyd's, London.</p>
                <p><strong>This Certificate</strong> is issued in accordance with the limited authorization granted to the Correspondent by certain Underwriters at Lloyd's, London whose syndicate numbers and the proportions underwritten by them can be ascertained from the office of the said Correspondent (such Underwriters being hereinafter called "Underwriters") and in consideration of the premium specified herein, Underwriters hereby bind themselves severally and not jointly, each for his own part and not one for another, their Executors and Administrators.</p>
                <p><strong>The Assured</strong> is requested to read this Certificate, and if it is not correct, return it immediately to the Correspondent for appropriate alteration.</p>
                <p>All inquiries regarding this Certificate should be addressed to the following Correspondent:</p>
                <p>As shown on Declarations Page</p>
            </blockquote>
        </blockquote>
    </div>

    <div class="row">
        <div class="col-12">
            <p><strong>SLC-3 (USA)</strong> NMA2868 (24/08/2000) </p>
        </div>
    </div>
</div>

<!-- Page 4 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p><strong>CERTIFICATE PROVISIONS</strong></p>
            <p><strong>1.</strong>   <strong>Signature Required.</strong> This Certificate shall not be valid unless signed by the Correspondent on the attached Declaration Page.</p>
            <p> <strong>2.</strong>   <strong>Correspondent Not Insurer.</strong> The Correspondent is not an Insurer hereunder and neither is nor shall be liable for any loss or claim whatsoever. The Insurers hereunder are those Underwriters at Lloyd's, London whose syndicate numbers can be ascertained as hereinbefore set forth. As used in this Certificate &quot;Underwriters&quot; shall be deemed to include incorporated as well as unincorporated persons or entities that are Underwriters at Lloyd's, London.</p>
            <p> <strong>3.</strong>   <strong>Cancellation.</strong> If this Certificate provides for cancellation and this Certificate is cancelled after the inception date, earned premium must be paid for the time the insurance has been in force.</p>
            <p><strong>4.</strong>   <strong>Service of Suit.</strong> It is agreed that in the event of the failure of Underwriters to pay any amount claimed to be due hereunder, Underwriters, at the request of the Assured, will submit to the jurisdiction of a Court of competent jurisdiction within the United States. Nothing in this Clause constitutes or should be understood to constitute a waiver of Underwriters' rights to commence an action in any Court of competent jurisdiction in the United States, to remove an action to a United States District Court, or to seek a transfer of a case to another Court as permitted by the laws of the United States or of any State in the United States. It is further agreed that service of process in such suit may be made upon the firm or person named in item 6 of the attached Declaration Page, and that in any suit instituted against any one of them upon this contract, Underwriters will abide by the final decision of such Court or of any Appellate Court in the event of an appeal.</p>
            <p>The above-named are authorized and directed to accept service of process on behalf of Underwriters in any such suit and/or upon request of the Assured to give a written undertaking to the Assured that they will enter a general appearance upon Underwriters' behalf in the event such a suit shall be instituted.</p>
            <p>Further, pursuant to any statute of any state, territory or district of the United States which makes provision therefor, Underwriters hereby designate the Superintendent, Commissioner or Director of Insurance or other officer specified for that purpose in the statute, or his successor or successors in office, as their true and lawful attorney upon whom may be served any lawful process in any action, suit or proceeding instituted by or on behalf of the Assured or any beneficiary hereunder arising out of this contract of insurance, and hereby designate the above-mentioned as the person to whom the said officer is authorized to mail such process or a true copy thereof.</p>
            <p><strong>5.</strong>   <strong>Assignment.</strong> This Certificate shall not be assigned either in whole or in part without the written consent of the Correspondent endorsed hereon.</p>
            <p><strong>6.</strong>   <strong>Attached Conditions Incorporated.</strong> This Certificate is made and accepted subject to all the provisions, conditions and warranties set forth herein, attached or endorsed, all of which are to be considered as incorporated herein.</p>
            <p><strong>7.</strong>   <strong>Short Rate Cancellation.</strong> If the attached provisions provide for cancellation, the table below will be used to calculate the short rate proportion of the premium when applicable under the terms of cancellation.</p>
        </div>
    </div>
</div>

<!-- Page 5 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p><strong>Short Rate Cancellation Table For Term of One Year.</strong></p>
            <table class="table table-borderless p-0">
                <tr>
                    <td>
                        <p>Days<br />
                            Insurance in<br />
                            Force</p>
                    </td>
                    <td>
                        <p class="text-center">Per Cent<br />
                            of one year<br />
                            Premium</p>
                    </td>
                    <td>
                        <p>Days<br />
                            Insurance in<br />
                            Force</p>
                    </td>
                    <td>
                        <p class="text-center">Per Cent<br />
                            of one year<br />
                            Premium</p>
                    </td>
                    <td>
                        <p>Days<br />
                            Insurance in<br />
                            Force</p>
                    </td>
                    <td>
                        <p class="text-center">Per Cent<br />
                            of one year<br />
                            Premium</p>
                    </td>
                    <td>
                        <p>Days<br />
                            Insurance in<br />
                            Force</p>
                    </td>
                    <td>
                        <p class="text-center">Per Cent<br />
                            of one year<br />
                            Premium</p>
                    </td>
                </tr>
            </table>
            <table class="table table-borderless p-0">
                <tr>
                    <td>
                        <p>  1    .........................   5%<br />
                            2    .........................   6<br />
                            3 -  4.........................   7<br />
                            5 -  6.........................   8<br />
                            7 -  8.........................   9<br />
                            9 - 10........................ 10<br />
                            11 - 12........................ 11<br />
                            13 - 14........................ 12<br />
                            15 - 16........................ 13<br />
                            17 - 18........................ 14<br />
                            19 - 20........................ 15<br />
                            21 - 22........................ 16<br />
                            23 - 25........................ 17<br />
                            26 - 29........................ 18<br />
                            30 - 32 ( 1  mos )........ 19<br />
                            33 - 36........................ 20<br />
                            37 - 40........................ 21<br />
                            41 - 43........................ 22<br />
                            44 - 47........................ 23<br />
                            48 - 51........................ 24<br />
                            52 - 54........................ 25<br />
                            55 - 58........................ 26<br />
                            59 - 62 ( 2  mos )........ 27<br />
                            63 - 65........................ 28 </p>
                    </td>
                    <td>
                        <p>  66 -   69...................... 29%<br />
                            70 -   73...................... 30<br />
                            74 -   76...................... 31<br />
                            77 -   80...................... 32<br />
                            81 -   83...................... 33<br />
                            84 -   87...................... 34<br />
                            88 -   91 ( 3  mos )...... 35<br />
                            92 -   94...................... 36<br />
                            95 -   98...................... 37<br />
                            99 - 102...................... 38<br />
                            103 - 105...................... 39<br />
                            106 - 109...................... 40<br />
                            110 - 113...................... 41<br />
                            114 - 116...................... 42<br />
                            117 - 120...................... 43<br />
                            121 - 124 ( 4  mos )...... 44<br />
                            125 - 127...................... 45<br />
                            128 - 131...................... 46<br />
                            132 - 135...................... 47<br />
                            136 - 138...................... 48<br />
                            139 - 142...................... 49<br />
                            143 - 146...................... 50<br />
                            147 - 149...................... 51<br />
                            150 - 153 ( 5  mos )...... 52 </p>
                    </td>
                    <td>
                        <p>154 - 156...................... 53%<br />
                            157 - 160...................... 54<br />
                            161 - 164...................... 55<br />
                            165 - 167...................... 56<br />
                            168 - 171...................... 57<br />
                            172 - 175...................... 58<br />
                            176 - 178...................... 59<br />
                            179 - 182 ( 6  mos )...... 60<br />
                            183 - 187...................... 61<br />
                            188 - 191...................... 62<br />
                            192 - 196...................... 63<br />
                            197 - 200...................... 64<br />
                            201 - 205...................... 65<br />
                            206 - 209...................... 66<br />
                            210 - 214 ( 7  mos )...... 67<br />
                            215 - 218...................... 68<br />
                            219 - 223...................... 69<br />
                            224 - 228...................... 70<br />
                            229 - 232...................... 71<br />
                            233 - 237...................... 72<br />
                            238 - 241...................... 73<br />
                            242 - 246 ( 8  mos )...... 74<br />
                            247 - 250...................... 75<br />
                            251 - 255...................... 76</p>
                    </td>
                    <td>
                        <p>256 - 260...................... 77%<br />
                            261 - 264...................... 78<br />
                            265 - 269...................... 79<br />
                            270 - 273 ( 9  mos )...... 80<br />
                            274 - 278...................... 81<br />
                            279 - 282...................... 82<br />
                            283 - 287...................... 83<br />
                            288 - 291...................... 84<br />
                            292 - 296...................... 85<br />
                            297 - 301...................... 86<br />
                            302 - 305 ( 10 mos )..... 87<br />
                            306 - 310...................... 88<br />
                            311 - 314...................... 89<br />
                            315 - 319...................... 90<br />
                            320 - 323...................... 91<br />
                            324 - 328...................... 92<br />
                            329 - 332...................... 93<br />
                            333 - 337 ( 11 mos )..... 94<br />
                            338 - 342...................... 95<br />
                            343 - 346...................... 96<br />
                            347 - 351...................... 97<br />
                            352 - 355...................... 98<br />
                            356 - 360...................... 99<br />
                            361 - 365 ( 12 mos )..... 100 </p>
                    </td>
                </tr>
            </table>
            <p>Rules applicable to insurance with terms less than or more than one year:</p>
            <p>A. If insurance has been in force for one year or less, apply the short rate table for annual insurance to the full annual premium determined as for insurance written for a term of one year.</p>
            <p>B.   If insurance has been in force for more than one year:<br />
                1.   Determine full annual premium as for insurance written for a term of one year.<br />
                2.   Deduct such premium from the full insurance premium, and on the remainder calculate the pro rata earned premium on the basis of the ratio of the length of time beyond one year the insurance has been in force to the length of time beyond one year for which the policy was originally written.<br />
                3.   Add premium produced in accordance with items (1) and (2) to obtain earned premium during full period insurance has been in force.</p>
        </div>
    </div>
</div>

<!-- Page 6 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p><strong>This Declaration Page is attached to and forms part of Certificate provisions (Form SLC-3 USA NMA2868)</strong></p>
            <p>Previous No. Authority Ref. No. <?= $bindAuthorityText ?> Policy No. <?= $policyNumber ?></p>
            <div>
                <p>
                    <strong>1</strong>.         Name and address of the Assured:
                </p>
                <blockquote>
                    <p>
                        <?php if ($entityType == 1): ?>
                            <?= $client->business_name ?><br>
                            <?= $client->business_name2 ?>
                        <?php else: ?>
                            <?= $client->first_name ?> <?= $client->last_name ?><br>
                            <?= $client->insured2_name ?>
                        <?php endif; ?><br>
                        <?= $client->address ?><br>
                        <?= $client->city ?>,&nbsp;<?= $client->state ?>&nbsp;<?= $client->zip ?>
                </blockquote>
            </div>
            <div>
                <p><strong>2</strong>.         Effective from <?= date('m/d/Y', strtotime($floodQuote->effectivity_date)) ?>          to <?= date('m/d/Y', strtotime($floodQuote->expiration_date)) ?></p>
            </div>
            <p>            both days at 12:01 a.m. standard time. <br />
            </p>
            <div><br />
                <strong>3</strong>.         Insurance is effective with certain UNDERWRITERS AT LLOYD'S, LONDON<strong>.</strong>
            </div>
            <p>Percentage:
            </p>
            <div>
                <p><strong>4.</strong>  
                <table class="table table-borderless p-0">
                    <tr class="btmbrdr">
                        <td class="text-center"><strong>Amount</strong></td>
                        <td><strong>Coverage</strong></td>
                        <td>&nbsp;</td>
                        <td class="text-end"><strong>Premium</strong></td>
                    </tr>
                    <tr>
                        <td class="text-center"><?= $formatter->formatCurrency(getMetaValue($floodQuoteMetas, "covABuilding", 0), 'USD') ?></td>
                        <td>Building</td>
                        <td>&nbsp;</td>
                        <td class="text-end">
                            <?=
                            ($policyType == "CAN")
                                ? $formatter->formatCurrency($cancelPremium, 'USD')
                                : $formatter->formatCurrency($calculations->finalPremium, 'USD')
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"><?= $formatter->formatCurrency(getMetaValue($floodQuoteMetas, "covABuilding", 0), 'USD') ?></td>
                        <td>Contents</td>
                        <td>&nbsp;</td>
                        <td class="text-end">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="text-center"><?= $formatter->formatCurrency(getMetaValue($floodQuoteMetas, "boundLossUseCoverage", 0), 'USD') ?></td>
                        <td>Loss of Use/Rents</td>
                        <td>&nbsp;</td>
                        <td class="text-end">&nbsp;</td>
                    </tr>
                </table>
            </div>

            <div>
                <p><strong>5.</strong>         Forms attached hereto and special conditions:    </p>
            </div>
            <div>
                <strong>6.</strong>         Service of Suit may be made upon:
                <blockquote>
                    Lloyd's America, Inc.<br />
                    Attention: Legal department <br />
                    280 Park Avenue, East Tower, 25th Floor<br>
                    New York, NY 10017
                </blockquote>
            </div>
            <div> <strong>7.</strong>         In the event of a claim, please notify the following:   </div>
            <blockquote>
                <p>
                    <?= $broker->name ?><br />
                    <?= $broker->address ?><br />
                    <?= $broker->city ?>, <?= $broker->state ?>&nbsp;&nbsp; <?= $broker->zip ?><br />
                    <?= $broker->phone ?>
                </p>
            </blockquote>
            <div>
                <p><strong>Dated <?= $boundDate ?>                                                                                      </strong>         <img src="<?= base_url('assets/images/jrwsig.jpg'); ?>" width="244" height="70"></p>
            </div>
        </div>
    </div>
</div>

<!-- Page 7 -->
<div class="content-wrapper">
    <div class="row mb-4">
        <div class="col text-center align-self-center">
            <img src="<?= base_url('assets/images/Lloyds.png'); ?>" width="180" height="75">
        </div>
    </div>
    <div class="row">
        <div class="col text-center align-self-center">
            <p>One Lime Street London EC3M 7HA</p>
        </div>
    </div>
</div>

<!-- Page 8 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>BRIT PRIMARY FLOOD INSURANCE POLICY</strong></p>
            <p class="text-center"><strong>DWELLING FORM</strong></p>
            <p class="text-center"><strong>PLEASE READ THIS POLICY CAREFULLY.</strong><br />
                THE FLOOD INSURANCE PROVIDED IS SUBJECT TO LIMITATIONS, RESTRICTIONS, AND EXCLUSIONS.</p>
            <p>THIS <strong>POLICY</strong> COVERS ONLY:</p>
            <p>A NON-CONDOMINIUM RESIDENTIAL <strong>BUILDING</strong> DESIGNED FOR PRINCIPAL USE AS A <strong>DWELLING</strong> PLACE OF ONE TO FOUR FAMILIES, OR A SINGLE FAMILY DWELLING UNIT IN A CONDOMINIUM BUILDING IS NOT INCLUDED.</p>
            <hr>
            <p class="text-center"><strong>I. AGREEMENT</strong></p>
            <hr>
            <p><strong>A. INSURING AGREEMENT</strong></p>
            <blockquote>
                <p>We will pay you for <strong>direct physical loss by or from Flood</strong> to your insured property if you:</p>
                <p>1. Have paid the correct premium;</p>
                <p>2. Comply with all terms and conditions of this <strong>policy</strong>; and</p>
                <p>3. Have furnished accurate information and statements.</p>
                <p>We have the right to review the information you give us at any time and to revise your policy based on our review.</p>
            </blockquote>
            <p><strong>B. NFIP COMPLIANCE</strong></p>
            <blockquote>
                <p>This policy meets the definition of private flood insurance contained in 42 U.S.C. 4012a(b)(7) and the corresponding regulations, or subsequent revisions of, in all respects when provided up to the maximum NFIP limits; therefore, to the extent that any provision within this policy fails to meet the definition of private flood insurance contained within the NFIP, such provision herein is hereby amended to conform to the minimum requirements of such definition. However, where limits exceed NFIP maximum available limits, this policy is not intended to comply or conform to NFIP coverage and the provisions within stand as written.</p>
                <p>As an alternative to this policy, flood insurance is available under the National Flood Insurance Program (NFIP) through an insurance agent who will obtain a policy either directly through the NFIP or through an insurance company that participates in the NFIP.</p>
            </blockquote>
            <hr>
            <p class="text-center"><strong>II. DEFINITIONS</strong></p>
            <hr>
            <p><strong>A</strong>. In this <strong>policy</strong>, &quot;you&quot; and &quot;your&quot; refer to the insured(s) shown on the <strong>Declarations Page</strong> of this <strong>policy</strong> and your spouse, if a resident of the same household. &quot;Insured(s)&quot; includes: Any mortgagee and loss payee named in the <strong>application</strong> and <strong>Declarations Page</strong>, as well as any other mortgagee or loss payee determined to exist at the time of loss in the order of precedence. &quot;We,&quot; &quot;us,&quot; and &quot;our&quot; refer to the insurer.</p>
        </div>
    </div>
</div>

<!-- Page 9 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <blockquote>
                <p>Some definitions are complex because they are provided as they appear in the law or regulations, or result from court cases. The precise definitions are intended to protect you.</p>
                <p><strong>Flood</strong>, as used in this flood insurance <strong>policy</strong>, means:</p>
                <p>1. A general and temporary condition of partial or complete inundation of two or more acres of normally dry land area or of two or more properties (one of which is your property) from:</p>
                <blockquote>
                    <p>a. Overflow of inland or tidal waters;</p>
                    <p>b. Unusual and rapid accumulation or runoff of surface waters from any source;</p>
                    <p>c. <strong>Mudflow</strong>.</p>
                </blockquote>
                <p>2. Collapse or subsidence of land along the shore of a lake or similar body of water as a result of erosion or undermining caused by waves or currents of water exceeding anticipated cyclical levels that result in a <strong>flood</strong> as defined in<strong> A.1.a.</strong> above.</p>
            </blockquote>
            <p><strong>B.</strong> The following are the other key definitions we use in this <strong>policy</strong>:</p>
            <blockquote>
                <p>1. <strong>Act</strong>. The National Flood Insurance Act of 1968, and any amendments to it.</p>
                <p>2. <strong>Actual Cash Value</strong>. The cost to replace an insured item of property at the time of loss, less the value of its physical depreciation.</p>
                <p>3. <strong>Application</strong>. The statement made and signed by you or your agent in applying for this <strong>policy</strong>. The application gives information we use to determine the eligibility of the risk, the kind of <strong>policy</strong> to be issued, and the correct premium payment. The <strong>application</strong> is part of this flood insurance <strong>policy</strong>. For us to issue you a policy, the correct premium payment must accompany the application.</p>
                <p>4.<strong> Base Flood</strong>. A <strong>flood</strong> having a one percent chance of being equaled or exceeded in any given year.</p>
                <p>5. <strong>Basement</strong>. Any area of the building, including any sunken room or sunken portion of a room, having its floor below ground level (subgrade) on all sides.</p>
                <p>6. <strong>Building</strong>. A structure, with two or more outside rigid walls and a fully secured roof, affixed to a permanent site; Building does not mean a gas or liquid storage tank or a manufactured or mobile home, recreational vehicle, park trailer, or other similar vehicle.</p>
                <p>7. <strong>Cancellation</strong>. The ending of the insurance coverage provided by this policy before the expiration date.</p>
                <p>8. <strong>Condominium</strong>. That form of ownership of real property in which each unit owner has an undivided interest in common elements</p>
                <p>9. <strong>Condominium Association</strong>. The entity made up of the unit owners responsible for the maintenance and operation of:</p>
                <blockquote>
                    <p>a. Common elements owned in undivided shares by unit owners; and<br />
                        b. Other real property in which the unit owners have use rights; where membership in the entity is a required condition of unit ownership.</p>
                </blockquote>
                <p>10. <strong>Declarations Page</strong>. A computer-generated summary of information you provided in the <strong>application</strong> for insurance. The<strong> Declarations Page</strong> also describes the term of the <strong>policy</strong>, limits of coverage, and displays the premium and our name. The<strong> Declarations Page</strong> is a part of this flood insurance <strong>policy</strong>.</p>
            </blockquote>
        </div>
    </div>
</div>

<!-- Page 10 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <blockquote>
                <p>11. Described Location. The location where the insured building(s) or personal property are found. The described location is shown on the Declarations Page.</p>
                <p>12. Direct Physical Loss By or From Flood. Loss or damage to insured property, directly caused by a flood. There must be evidence of physical changes to the property.</p>
                <p>13. Dwelling. A building designed for use as a residence for no more than four families or a single-family unit in a building under a condominium form of ownership.</p>
                <p>14. Elevated Building. A building that has no basement and that has its lowest elevated floor raised above ground level by foundation walls, shear walls, posts, piers, pilings, or columns.</p>
                <p>15. Emergency Program. The initial phase of a community's participation in the National Flood Insurance Program. During this phase, only limited amounts of insurance are available under the Act.</p>
                <p>16. Additions and alterations. The additions, fixtures, alterations, improvements, installations, or other items of real property comprising a part of the dwelling or the apartment in which you reside.</p>
                <p>17. Mudflow. A river of liquid and flowing mud on the surface of normally dry land areas, as when earth is carried by a current of water. Other earth movements, such as landslide, slope failure, or a saturated soil mass moving by liquidity down a slope, are not mudflows.</p>
                <p>18. National Flood Insurance Program (NFIP). The program of flood insurance coverage and floodplain management administered under the Act and applicable Federal regulations in Title 44 of the Code of Federal Regulations, Subchapter B.</p>
                <p>19. Non-Participating Community. Communities which do not participate in the NFIP, see NFIP Community Status Book available through FEMA for an updated list.</p>
                <p>20. Policy. The entire written contract between you and us. It includes:</p>
                <blockquote>
                    <p>a. This printed form;</p>
                    <p>b. The application and Declarations Page;</p>
                    <p>c. Any endorsement(s) that may be issued; and</p>
                    <p>d. Any renewal certificate indicating that coverage has been instituted for a new policy and new policy term.</p>
                    <p>Only one dwelling, which you specifically described in the application, may be insured under this policy.</p>
                </blockquote>
                <p>21. Pollutants. Substances that include, but are not limited to, any solid, liquid, gaseous, or thermal irritant or contaminant, including smoke, vapor, soot, fumes, acids, alkalis, chemicals, and waste. &quot;Waste&quot; includes, but is not limited to, materials to be recycled, reconditioned, or reclaimed.</p>
                <p>22. Post-FIRM Building. A building for which construction or substantial improvement occurred after December 31, 1974, or on or after the effective date of an initial Flood Insurance Rate Map (FIRM), whichever is later.<br />
                </p>
                <p>23. Special Flood Hazard Area. An area having special flood or mudflow, and/or flood-related erosion hazards, and shown on a Flood Hazard Boundary Map or Flood Insurance Rate Map as Zone A, AO, A1–A30, AE, A99, AH, AR, AR/A, AR/AE, AR/AH, AR/AO, AR/A1–A30, V1–V30, VE, or V.</p>
                <p>24. Unit. A single-family unit you own in a condominium building.</p>
            </blockquote>
        </div>
    </div>
</div>

<!-- Page 11 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <blockquote>
                <p>25. Valued Policy. A policy in which the insured and the insurer agree on the value of the property insured, that value being payable in the event of a total loss. This Primary Flood Insurance Policy is not a valued policy.</p>
            </blockquote>
            <p class="text-center"><strong>III. PROPERTY COVERED</strong></p>
            <p>A. COVERAGE A - BUILDING PROPERTY</p>
            <blockquote>
                <p>We insure against direct physical loss by or from Flood to:</p>
                <p>1. The Dwelling at the described location, or for a period of 45 days at another location as set forth in III.C.2.b., Property Removed to Safety.</p>
                <p>2. Additions and extensions attached to and in contact with the dwelling by means of a rigid exterior wall, a solid load-bearing interior wall, a stairway, an elevated walkway, or a roof. At your option, additions and extensions connected by any of these methods may be separately insured. Additions and extensions attached to and in contact with the building by means of a common interior wall that is not a solid load-bearing wall are always considered part of the dwelling and cannot be separately insured.</p>
                <p>3. A detached garage at the described location. Coverage is limited to no more than 10% of the limit of liability on the dwelling. Use of this insurance is at your option but reduces the building limit of liability. We do not cover any detached garage used or held for use for residential (i.e., dwelling), business, or farming purposes.</p>
                <p>4. Materials and supplies to be used for construction, alteration, or repair of the dwelling or a detached garage while the materials and supplies are stored in a fully enclosed building at the described location or on an adjacent property.</p>
                <p>5. A building under construction, alteration, or repair at the described location.</p>
                <blockquote>
                    <p>a. If the structure is not yet walled or roofed as described in the definition for building (see II.B.8.) then coverage applies:</p>
                    <blockquote>
                        <p>(1) Only while such work is in progress; or</p>
                        <p>(2) If such work is halted, only for a period of up to 90 continuous days thereafter.</p>
                    </blockquote>
                    <p>b. However, coverage does not apply until the building is walled and roofed if the lowest floor, including the basement floor, of a non-elevated building or the lowest elevated floor of an elevated building is:</p>
                    <blockquote>
                        <p>(1) Below the base flood elevation in Zones AH, AE, A1-A30, AR, AR/AE, AR/AH, AR/A1-A30, AR/A, AR/AO; or</p>
                        <p>(2) Below the base flood elevation adjusted to include the effect of wave action in Zones VE or V1-V30.</p>
                    </blockquote>
                    <p>The lowest floor levels are based on the bottom of the lowest horizontal structural member of the floor in Zones VE or V1–V30 and the top of the floor in Zones AH, AE, A1–A30, AR, AR/AE, AR/AH, AR/A1–A30, AR/A, AR/AO.</p>
                </blockquote>
                <p>6. The following items of property which are covered under Coverage A only:</p>
            </blockquote>
        </div>
    </div>
</div>

<!-- Page 12 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <blockquote>
                <blockquote>
                    <p>a. Awnings and canopies;<br />
                        b. Blinds;<br />
                        c. Built-in dishwashers;<br />
                        d. Built-in microwave ovens;<br />
                        e. Carpet permanently installed over unfinished flooring;<br />
                        f. Central air conditioners;<br />
                        g. Elevator equipment;<br />
                        h. Fire sprinkler systems;<br />
                        i. Walk-in freezers;<br />
                        j. Furnaces and radiators;<br />
                        k. Garbage disposal units;<br />
                        l. Hot water heaters, including solar water heaters;<br />
                        m. Light fixtures;<br />
                        n. Outdoor antennas and aerials fastened to buildings;<br />
                        o. Permanently installed cupboards, bookcases, cabinets, paneling, and wallpaper;<br />
                        p. Plumbing fixtures;<br />
                        q. Pumps and machinery for operating pumps;<br />
                        r. Ranges, cooking stoves, and ovens;<br />
                        s. Refrigerators; and<br />
                        t. Wall mirrors, permanently installed.</p>
                </blockquote>
                <p>7. Items of property in a building enclosure below the lowest elevated floor of an elevated post-FIRM building located in Zones A1–A30, AE, AH, AR, AR/A, AR/AE, AR/AH, AR/A1–A30, V1–V30, or VE, or in a basement, regardless of the zone. Coverage is limited to the following:</p>
                <blockquote>
                    <p>a. Any of the following items, if installed in their functioning locations and, if necessary for operation, connected to a power source:</p>
                    <blockquote>
                        <p>(1) Central air conditioners;<br />
                            (2) Cisterns and the water in them;<br />
                            (3) Drywall for walls and ceilings in a basement and the cost of labor to nail it, unfinished and unfloated and not taped, to the framing;<br />
                            (4) Electrical junction and circuit breaker boxes;<br />
                            (5) Electrical outlets and switches;<br />
                            (6) Elevators, dumbwaiters, and related equipment, except for related equipment installed below the base flood elevation after September 30, 1987;<br />
                            (7) Fuel tanks and the fuel in them;<br />
                            (8) Furnaces and hot water heaters;<br />
                            (9) Heat pumps;<br />
                            (10) Nonflammable insulation in a basement;<br />
                            (11) Pumps and tanks used in solar energy systems;<br />
                            (12) Stairways and staircases attached to the building, not separated from it by elevated walkways;<br />
                            (13) Sump pumps;<br />
                            (14) Water softeners and the chemicals in them, water filters, and faucets installed as an integral part of the plumbing system;<br />
                            (15) Well water tanks and pumps;<br />
                            (16) Required utility connections for any item in this list; and<br />
                            (17) Footings, foundations, posts, pilings, piers, or other foundation walls and anchorage systems required to support a building.</p>
                    </blockquote>
                </blockquote>
                <p>b. Clean-up.</p>
            </blockquote>
            <p>B. COVERAGE B - PERSONAL PROPERTY</p>
            <blockquote>
                <p>1. If you have purchased personal property coverage, we insure against direct physical loss by or from Flood to personal property inside a building at the described location, if:</p>
            </blockquote>
        </div>
    </div>
</div>

<!-- Page 13 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <blockquote>
                <blockquote>
                    <blockquote>
                        <p>a. The property is owned by you or your household family members; and</p>
                        <p>b. At your option, the property is owned by guests or servants.</p>
                    </blockquote>
                    <p>Personal property is also covered for a period of 45 days at another location as set forth in III.C.2.b., Property Removed to Safety.</p>
                    <p>Personal property in a building that is not fully enclosed must be secured to prevent flotation out of the building. If the personal property does float out during a flood, it will be conclusively presumed that it was not reasonably secured. In that case there is no coverage for such property.</p>
                </blockquote>
                <p>2. Coverage for personal property includes the following property, subject to B.1. above, which is covered under Coverage B only:</p>
                <blockquote>
                    <p>a. Air conditioning units, portable or window type;<br />
                        b. Carpets, not permanently installed, over unfinished flooring;<br />
                        c. Carpets over finished flooring;<br />
                        d. Clothes washers and dryers;<br />
                        e. &quot;Cook-out&quot; grills;<br />
                        f. Food freezers, other than walk-in, and food in any freezer; and<br />
                        g. Portable microwave ovens and portable dishwashers.</p>
                </blockquote>
                <p>3. Coverage for items of property in a building enclosure below the lowest elevated floor of an elevated post-FIRM building or in a basement, regardless of the zone, is limited to the following items, if installed in their functioning locations and, if necessary for operation, connected to a power source:</p>
                <blockquote>
                    <p>a. Air conditioning units, portable or window type;<br />
                        b. Clothes washers and dryers; and<br />
                        c. Food freezers, other than walk-in, and food in any freezer.</p>
                </blockquote>
                <p>5. If you are a tenant and have insured personal property under Coverage B in this policy, we will cover such property, including your cooking stove or range and refrigerator. The policy will also cover improvements made or acquired solely at your expense in the dwelling or apartment in which you reside, but for not more than 10% of the limit of liability shown for personal property on the Declarations Page. Use of this insurance is at your option but reduces the personal property limit of liability.</p>
                <p>6. If you are the owner of a unit and have insured personal property under Coverage B in this policy, we will also cover your interior walls, floor, and ceiling (not otherwise covered under a flood insurance policy purchased by your condominium association) for not more than 10% of the limit of liability shown for personal property on the Declarations Page. Use of this insurance is at your option but reduces the personal property limit of liability.</p>
                <p>7. Special Limits. We will pay no more than $2,500 for any one loss to one or more of the following kinds of personal property:</p>
                <blockquote>
                    <p>a. Artwork, photographs, collectibles, or memorabilia, including but not limited to, porcelain or other figures, and sports cards;<br />
                        b. Rare books or autographed items;<br />
                        c. Jewelry, watches, precious and semi-precious stones, or articles of gold, silver, or platinum;<br />
                        d. Furs or any article containing fur which represents its principal value; or<br />
                        e. Personal property used in any business.</p>
                </blockquote>
                <p>8. For antique items not listed under B.7 above we will pay only for the functional replacement cost value of antiques.</p>
                <p>Functional replacement cost value means the amount which it would cost to repair or replace the damaged item with less costly common materials and methods which are functionally equivalent to obsolete, antique or custom materials and methods used in the original making of the item.</p>
            </blockquote>
        </div>
    </div>
</div>

<!-- Page 14 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p>C. COVERAGE C - OTHER COVERAGES</p>
            <blockquote>
                <p>1. Debris Removal</p>
                <blockquote>
                    <p>a. We will pay the expense to remove non-owned debris that is on or in insured property and debris of insured property anywhere.</p>
                    <p>b. If you or members of your household perform the removal work, the value of your work will be based on the Federal minimum wage.</p>
                    <p>c. This coverage does not increase the Coverage A or Coverage B limit of liability.</p>
                </blockquote>
                <p>2. Loss Avoidance Measures</p>
                <blockquote>
                    <p>a. Sandbags, Supplies, and Labor</p>
                    <blockquote>
                        <p>(1) We will pay up to $1,000 for costs you incur to protect the insured building from an actual flood or imminent danger of flood, for the following:</p>
                        <blockquote>
                            <p>(a) Your reasonable expenses to buy:</p>
                            <blockquote>
                                <p>(i) Sandbags, including sand to fill them;</p>
                                <p>(ii) Fill for temporary levees;</p>
                                <p>(iii) Pumps; and</p>
                                <p>(iv) Plastic sheeting and lumber used in connection with these items.</p>
                            </blockquote>
                            <p>(b) The value of work, at the Federal minimum wage, you or a member of your household perform.</p>
                        </blockquote>
                        <p>(2) This coverage for Sandbags, Supplies, and Labor only applies if damage to insured property by or from flood is imminent, and the threat of flood damage is apparent enough to lead a person of common prudence to anticipate flood damage. One of the following must also occur:</p>
                        <blockquote>
                            <p>(a) A general and temporary condition of flooding in the area near the described location must occur, even if the flood does not reach the building; or</p>
                            <p>(b) A legally authorized official must issue an evacuation order or other civil order for the community in which the building is located calling for measures to preserve life and property from the peril of flood.</p>
                        </blockquote>
                        <p> This coverage does not increase the Coverage A or Coverage B limit of liability.</p>
                    </blockquote>
                    <p>b. Property Removed to Safety</p>
                    <blockquote>
                        <p>(1) We will pay up to $1,000 for the reasonable expenses you incur to move insured property to a place other than the described location that contains the property in order to protect it from flood or the imminent danger of flood. Reasonable expenses include the value of work, at the Federal minimum wage, you or a member of your household perform.</p>
                        <p>(2) If you move insured property to a location other than the described location that contains the property, in order to protect it from flood or the imminent danger of flood, we will cover such property while at that location for a period of 45 consecutive days from the date you begin to</p>
                    </blockquote>
                </blockquote>
            </blockquote>
        </div>
    </div>
</div>

<!-- Page 15 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <blockquote>
                <blockquote>
                    <blockquote>
                        <p>move it there. The personal property that is moved must be placed in a fully enclosed building or otherwise reasonably protected from the elements.</p>
                    </blockquote>
                    <p>Any property removed must be placed above ground level or outside of the special flood hazard area.</p>
                    <p>This coverage does not increase the Coverage A or Coverage B limit of liability.</p>
                </blockquote>
                <p>3. Condominium Loss Assessments</p>
                <blockquote>
                    <p>a. If this policy insures a unit, we will pay, up to the Coverage A limit of liability, your share of loss assessments charged against you by the condominium association in accordance with the condominium association’s articles of association, declarations and your deed.</p>
                    <p>The assessment must be made as a result of direct physical loss by or from flood during the policy term, to the building’s common elements.</p>
                    <p>b. We will not pay any loss assessment charged against you:</p>
                    <blockquote>
                        <p>(1) And the condominium association by any government body;</p>
                        <p>(2) That results from a deductible under the insurance purchased by the condominium association insuring common elements;</p>
                        <p>(3) That results from a loss to personal property, including contents of a condominium building;</p>
                        <p>(4) That results from a loss sustained by the condominium association that was not reimbursed under a flood insurance policy written in the name of the association under the Act because the building was not, at the time of loss, insured for an amount equal to the lesser of:</p>
                        <blockquote>
                            <p>(a) 80% or more of its full replacement cost; or<br />
                                (b) The maximum amount of insurance permitted under the Act.</p>
                        </blockquote>
                        <p>(5) To the extent that payment under this policy for a condominium building loss, in combination with payments under any other NFIP policies for the same building loss, exceeds the maximum amount of insurance permitted under the Act for that kind of building; or</p>
                        <p>(6) To the extent that payment under this policy for a condominium building loss, in combination with any recovery available to you as a tenant in common under any NFIP condominium association policies for the same building loss, exceeds the amount of insurance permitted under the Act for a single-family dwelling.</p>
                    </blockquote>
                    <p>Loss assessment coverage does not increase the Coverage A limit of liability.</p>
                </blockquote>
            </blockquote>
            <p>D. COVERAGE D - INCREASED COST OF COMPLIANCE</p>
            <blockquote>
                <p>1. General</p>
                <blockquote>
                    <p>This policy pays you to comply with a State or local floodplain management law or ordinance affecting repair or reconstruction of a structure suffering flood damage. Compliance activities eligible for payment</p>
                </blockquote>
            </blockquote>
        </div>
    </div>
</div>

<!-- Page 16 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <blockquote>
                <blockquote>
                    <p>are: elevation, floodproofing, relocation, or demolition (or any combination of these activities) of your structure. Eligible floodproofing activities are limited to:</p>
                    <blockquote>
                        <p>(a) Non-residential structures.</p>
                        <p>(b) Residential structures with basements that satisfy FEMA’s standards published in the Code of Federal Regulations [44 CFR 66.6 (b) or (c)].</p>
                    </blockquote>
                </blockquote>
                <p>2. Limit of Liability</p>
                <blockquote>
                    <p>We will pay you up to $30,000 under this Coverage D- Increased Cost of Compliance, which only applies to policies with a building coverage (Coverage A) limit of liability listed on the Declarations Page. Our payment of claims under Coverage D is in addition to the amount of coverage you selected and which appears on the Declarations Page for Coverage A. However, the maximum you can collect under this policy for both Coverage A and Coverage D cannot exceed the maximum permitted under the Act. We do not charge a separate deductible for a claim under Coverage D.</p>
                </blockquote>
                <p>3. Eligibility</p>
                <blockquote>
                    <p>a. A structure covered under Coverage A sustaining a loss caused by a flood as defined by this policy must be a structure that has had flood damage in which the cost to repair equals or exceeds 25% of the market value of the structure at the time of the flood. The State or community must have a substantial damage provision in its floodplain management law or ordinance being enforced against the structure.</p>
                    <p>b. This Coverage D pays you to comply with State or local floodplain management laws or ordinances that meet the minimum standards of the NFIP found in the Code of Federal Regulations at 44 CFR 60.3. We pay for compliance activities that exceed those standards under these conditions:</p>
                    <blockquote>
                        <p>(1) 3.a. above.</p>
                        <p>(2) Elevation or floodproofing in any risk zone to preliminary or advisory base flood elevations provided by FEMA which the State or local government has adopted and is enforcing for flood-damaged structures in such areas. (This includes compliance activities in B, C, X or D zones which are being changed to zones with base flood elevations. This also includes compliance activities in zones where base flood elevations are being increased, and a flood-damaged structure must comply with the higher advisory base flood elevation.) Increased Cost of Compliance coverage does not apply to situations in B, C, X or D zones where the community has derived its own elevations and is enforcing elevation or floodproofing requirements for flood-damaged structures to elevations derived solely by the community.</p>
                        <p>(3) Elevation or floodproofing above the base flood elevation to meet State or local &quot;freeboard&quot; requirements, i.e., that a structure must be elevated above the base flood elevation.</p>
                    </blockquote>
                    <p>c. Under the minimum NFIP criteria at 44 CFR 60.3 (b)(4), States and communities must require the elevation or floodproofing of structures in unnumbered A zones to the base flood elevation where elevation data is obtained from a Federal, State, or other source. Such compliance activities are also eligible for Coverage D. This coverage will also pay for the incremental cost, after demolition or relocation, of elevating or floodproofing a structure during its rebuilding at the same or another site to meet State or local floodplain management laws or ordinances, subject to Exclusion D.5.e. below.</p>
                    <p>d. This coverage will also pay for the incremental cost, after demolition or relocation, of elevating or floodproofing a structure during its rebuilding at the same or another site to meet State or local floodplain management laws or ordinances, subject to Exclusion D.5.g. below.</p>
                </blockquote>
            </blockquote>
        </div>
    </div>
</div>

<!-- Page 17 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <blockquote>
                <blockquote>
                    <p>e. This coverage will also pay to bring a flood-damaged structure into compliance with State or local floodplain management laws or ordinances even if the structure had received a variance before the present loss from the applicable floodplain management requirements.</p>
                </blockquote>
                <p>4. Conditions</p>
                <blockquote>
                    <p>a. When a structure covered under Coverage A - Building Property sustains a loss caused by a flood, our payment for the loss under this Coverage D will be for the increased cost to elevate, floodproof, relocate, or demolish (or any combination of these activities) caused by the enforcement of current State or local floodplain management ordinances or laws. Our payment for eligible demolition activities will be for the cost to demolish and clear the site of the building debris or a portion thereof caused by the enforcement of current State or local floodplain management ordinances or laws. Eligible activities for the cost of clearing the site will include those necessary to discontinue utility service to the site and ensure proper abandonment of on-site utilities.
                        <br />b. When the building is repaired or rebuilt, it must be intended for the same occupancy as the present building unless otherwise required by current floodplain management ordinances or laws.
                    </p>
                </blockquote>
                <p>5. Exclusions</p>
                <blockquote>
                    <p>Under this Coverage D (Increased Cost of Compliance) we will not pay for:</p>
                    <p>a. The costs to make property not covered under this policy comply with any floodplain management law or ordinance in communities participating in the Emergency Program.</p>
                    <p>b. The cost associated with enforcement of any ordinance or law that requires any insured or others to test for, monitor, clean up, remove, contain, treat, detoxify or neutralize, or in any way respond to, or assess the effects of pollutants.</p>
                    <p>c. The loss in value to any insured building or other structure due to the requirements of any ordinance or law.</p>
                    <p>d. The loss in residual value of the undamaged portion of a building demolished as a con-sequence of enforcement of any State or local floodplain management law or ordinance.</p>
                    <p>e. Any Increased Cost of Compliance under this Coverage D:</p>
                    <blockquote>
                        <p>(1) Until the building is elevated, floodproofed, demolished, or relocated on the same or to another premises; and
                            <br />(2) Unless the building is elevated, floodproofed, demolished, or relocated as soon as reasonably possible after the loss, not to exceed two years.
                        </p>
                    </blockquote>
                    <p>f. Any code upgrade requirements, e.g., plumbing or electrical wiring, not specifically related to the State or local floodplain management law or ordinance.</p>
                    <p>g. Any compliance activities needed to bring additions or improvements made after the loss occurred into compliance with State or local floodplain management laws or ordinances.</p>
                    <p>h. Any rebuilding activity to standards that do not meet the NFIP’s minimum requirements. This includes any situation where the insured has received from the state or community a variance in connection with the current flood loss to rebuild the property to an elevation below the base flood elevation.</p>
                    <p>i. Increased Cost of Compliance for a garage or carport.</p>
                    <p>j. Loss due to any ordinance or law that you were required to comply with before the current loss.</p>
                </blockquote>
            </blockquote>
        </div>
    </div>
</div>

<!-- Page 18 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <blockquote>
                <blockquote>
                    <p>k. Any structure insured under an NFIP Group Flood Insurance Policy, or similar Private Flood Policy.</p>
                    <p>l. Assessments made by condominium association on individual condominium unit owners to pay increased costs of repairing commonly owned buildings after a flood in compliance with State or local floodplain management ordinances or laws.</p>
                    <p>m. The costs to make property not covered under this policy comply with any floodplain management law or ordinance.</p>
                </blockquote>
                <p>6. Other Provisions</p>
                <blockquote>
                    <p>All other exclusions, conditions and provisions of this policy apply.</p>
                </blockquote>
            </blockquote>
            <p style="text-align: center"><strong>IV. PROPERTY NOT COVERED</strong></p>


            <p>We do not cover any of the following:</p>
            <p>1. Personal property not inside a building;</p>
            <p>2. A building, and personal property in it, located entirely in, on, or over water or seaward of mean high tide if it was constructed or substantially improved after September 30, 1982;</p>
            <p>3. Open structures, including a building used as a boathouse or any structure or building into which boats are floated, and personal property located in, on, or over water;</p>
            <p>4. Recreational vehicles, travel trailers, motor homes, manufactured homes and mobile homes, even if affixed to a permanent foundation;</p>
            <p>5. Self-propelled vehicles or machines, including their parts and equipment. However, we do cover self-propelled vehicles or machines not licensed for use on public roads that are:</p>
            <p>a. Used mainly to service the described location, or</p>
            <p>b. Designed and used to assist handicapped persons, while the vehicles or machines are inside a building at the described location;</p>
            <p>6. Land, land values, lawns, trees, shrubs, plants, growing crops, or animals;</p>
            <p>7. Accounts, bills, coins, currency, deeds, evidences of debt, medals, money, scrip, stored value cards, postage stamps, securities, bullion, manuscripts, or other valuable papers;</p>
            <p>8. Underground structures and equipment, including wells, septic tanks, and septic systems;</p>
            <p>9. Those portions of walks, walkways, decks, driveways, patios and other surfaces, all whether protected by a roof or not, located outside the perimeter, exterior walls of the insured building or the building in which the insured unit is located;</p>
            <p>10. Containers, including related equipment, such as, but not limited to, tanks containing gases or liquids;</p>
            <p>11. Buildings and all their contents if more than 49% of the actual cash value of the building is below ground, unless the lowest level is at or above the base flood elevation and is below ground by reason of earth having been used as insulation material in conjunction with energy efficient building techniques;</p>
        </div>
    </div>
</div>

<!-- Page 19 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p>12. Fences, retaining walls, seawalls, bulkheads, wharves, piers, bridges, and docks;</p>
            <p>13. Aircraft or watercraft, or their furnishings and equipment;</p>
            <p>14. Hot tubs and spas that are not bathroom fixtures, and swimming pools, and their equipment such as, but not limited to, heaters, filters, pumps, and pipes, wherever located;</p>
            <p>15. Property not eligible for flood insurance pursuant to the provisions of the Coastal Barrier Resources Act and the Coastal Barrier Improvement Act and amendments to these acts;</p>
            <p>16. Personal property you own in common with other unit owners comprising the membership of a condominium association.</p>
            <p>17. Property located within a Non-Participating Community or within a community covered under the Emergency Program.</p>
            <p class="text-center"><strong>V. EXCLUSIONS</strong></p>
            <p>A. We only pay for direct physical loss by or from Flood, which means that we do not pay you for:</p>
            <blockquote>
                <p>1. Loss of revenue or profits;</p>
                <p>2. Loss of use of, or access to, the insured property or described location;</p>
                <p>3. Loss from interruption of business or production;</p>
                <p>4. Any additional living expenses or lost rents incurred while the insured building is being repaired or is unable to be occupied for any reason;</p>
                <p>5. The cost of complying with any ordinance or law requiring or regulating the construction, demolition, remodeling, renovation, or repair of property, including removal of any resulting debris. This exclusion does not apply to any eligible activities we describe in Coverage D - Increased Cost of Compliance; or</p>
                <p>6. Any other economic loss you suffer.</p>
            </blockquote>
            <p>B. We do not insure a loss directly or indirectly caused by a flood that is already in progress at the time and date:</p>
            <blockquote>
                <p>1. The policy term begins; or</p>
                <p>2. Coverage is added at your request.</p>
            </blockquote>
            <p>C. We do not insure for loss to property caused directly by earth movement, even if the earth movement is caused by flood. Some examples of earth movement that we do not cover are:</p>
            <blockquote>
                <p>1. Earthquake;</p>
                <p>2. Landslide;</p>
                <p>3. Land subsidence;</p>
                <p>4. Sinkholes</p>
            </blockquote>
        </div>
    </div>
</div>

<!-- Page 20 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <blockquote>
                <p>4. Destabilization or movement of land that results from accumulation of water in subsurface land area; or</p>
                <p>5. Gradual erosion.</p>
                <p>We do, however, pay for losses from mudflow and land subsidence as a result of erosion that are specifically covered under our definition of flood (see II.A.1.c. and II.A.2.).</p>
            </blockquote>
            <p>D. We do not insure for direct physical loss caused directly or indirectly by any of the following:</p>
            <blockquote>
                <p>1. The pressure or weight of ice;</p>
                <p>2. Freezing or thawing;</p>
                <p>3. Rain, snow, sleet, hail, or water spray;</p>
                <p>4. Water, moisture, mildew, or mold damage that results primarily from any condition:</p>
                <blockquote>
                    <p>a. Substantially confined to the dwelling; or</p>
                    <p>b. That is within your control, including but not limited to:</p>
                    <blockquote>
                        <p>(1) Design, structural, or mechanical defects;</p>
                        <p>(2) Failure, stoppage, or breakage of water or sewer lines, drains, pumps, fixtures, or equipment; or</p>
                        <p>(3) Failure to inspect and maintain the property after a flood recedes;</p>
                    </blockquote>
                </blockquote>
                <p>5. Water or water-borne material that:</p>
                <blockquote>
                    <p>a. Backs up through sewers or drains;</p>
                    <p>b. Discharges or overflows from a sump, sump pump, or related equipment; or</p>
                    <p>c. Seeps or leaks on or through the covered property;<br />
                        unless there is a flood in the area and the flood is the proximate cause of the sewer or drain backup, sump pump discharge or overflow, or seepage of water;</p>
                </blockquote>
                <p>6. The pressure or weight of water unless there is a flood in the area and the flood is the proximate cause of the damage from the pressure or weight of water;</p>
                <p>7. Power, heating, or cooling failure unless the failure results from direct physical loss by or from flood to power, heating, or cooling equipment on the described location;</p>
                <p>8. Theft, fire, explosion, wind, or windstorm;</p>
                <p>9. Anything you or any member of your household do or conspires to do to deliberately cause loss by flood; or</p>
                <p>10. Alteration of the insured property that significantly increases the risk of flooding.</p>
            </blockquote>
            <p>E. We do not insure for loss to any building or personal property located on land leased from the Federal Government, arising from or incident to the flooding of the land by the Federal Government, where the lease expressly holds the Federal Government harmless under flood insurance issued under any Federal Government program.</p>
        </div>
    </div>
</div>

<!-- Page 21 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p>F. We do not pay for the testing for or monitoring of pollutants unless required by law or ordinance.</p>
            <p class="text-center"><strong>VI. DEDUCTIBLES</strong></p>
            <p>A. When a loss is covered under this policy, we will pay only that part of the loss that exceeds your deductible amount, subject to the limit of liability that applies. The deductible amount is shown on the Declarations Page.</p>
            <blockquote>
                <p>However, when a building under construction, alteration, or repair does not have at least two rigid exterior walls and a fully secured roof at the time of loss, your deductible amount will be two times the deductible that would otherwise apply to a completed building.</p>
            </blockquote>
            <p>B. In each loss from flood, separate deductibles apply to Coverage A - Building Property and Coverage B - Personal Property insured by this policy.</p>
            <p>C. The deductible does not apply to:</p>
            <blockquote>
                <p>1. III.C.2. Loss Avoidance Measures;</p>
                <p>2. III.C.3. Association Loss Assessments; or</p>
                <p>3. III.D. Increased Cost of Compliance.</p>
            </blockquote>
            <p class="text-center"><strong>VII. GENERAL CONDITIONS</strong></p>
            <p>A. Pair and Set Clause</p>
            <blockquote>
                <p>In case of loss to an article that is part of a pair or set, we will have the option of paying you:</p>
                <p>1. An amount equal to the cost of replacing the lost, damaged, or destroyed article, minus its depreciation; or</p>
                <p>2. The amount that represents the fair proportion of the total value of the pair or set that the lost, damaged, or destroyed article bears to the pair or set.</p>
            </blockquote>
            <p>B. Concealment or Fraud and Policy Voidance</p>
            <blockquote>
                <p>1. With respect to all insureds under this policy, this policy:</p>
                <blockquote>
                    <p>a. Is void;</p>
                    <p>b. Has no legal force or effect;</p>
                    <p>c. Cannot be renewed; and</p>
                    <p>d. Cannot be replaced by a new policy, if, before or after a loss, you or any other insured or your agent have at any time:</p>
                    <blockquote>
                        <p>(1) Intentionally concealed or misrepresented any material fact or circumstance;</p>
                    </blockquote>
                </blockquote>
            </blockquote>
        </div>
    </div>
</div>

<!-- Page 22 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <blockquote>
                <blockquote>
                    <p>(2) Engaged in fraudulent conduct; or</p>
                    <p>(3) Made false statements; relating to this policy or any other insurance issued by us.</p>
                </blockquote>
                <p>2. This policy will be void as of the date wrongful acts described in B.1. above were committed.</p>
                <p>3. Fines, civil penalties, and imprisonment under applicable Federal laws may also apply to the acts of fraud or concealment described above.</p>
            </blockquote>
            <p>C. Other Insurance</p>
            <blockquote>
                <p>If a loss covered by this policy is also covered by other insurance that includes Flood coverage, we will not pay more than the amount of insurance you are entitled to for lost, damaged, or destroyed property insured under this policy subject to the following:</p>
                <p>a. We will pay only the proportion of the loss that the amount of insurance that applies under this policy bears to the total amount of insurance covering the loss, unless C.1.b. or c. immediately below applies.</p>
                <p>b. If the other policy was designed to provide excess coverage, and has a provision stating that it is excess insurance, this policy will be primary.</p>
                <p>c. This policy will be primary (but subject to its own deductible) up to the deductible in the other flood policy (except another policy as described in C.1.b. above). When the other deductible amount is reached, this policy will participate in the same proportion that the amount of insurance under this policy bears to the total amount of both policies, for the remainder of the loss.</p>
                <p>d. If there is other insurance in the name of your condominium association covering the same property covered by this policy, then this policy will be in excess over the other insurance.</p>
            </blockquote>
            <p>D. Amendments, Waivers, Assignment</p>
            <blockquote>
                <p>This policy cannot be changed nor can any of its provisions be waived without our express written consent. No action that we take under the terms of this policy constitutes a waiver of any of our rights. You may not assign this policy.</p>
            </blockquote>
            <p>E. Cancellation of Policy by You</p>
            <blockquote>
                <p>You may cancel this policy at any time by returning it to us or notifying us in writing of the future date that the cancellation is to take effect. If you cancel this policy, you will be entitled to a pro-rata refund of the premium, except in the event of a claim as then this policy will be fully earned.</p>
            </blockquote>
            <p>F. Non-renewal or Cancellation of the Policy by Us</p>
            <blockquote>
                <p>We may elect to non-renew or to cancel this policy by sending written notice to your mailing address shown on the Declarations Page, at least 45 days prior to the effective date of the non-renewal or cancellation. Our written notice to you shall indicate the reason or reasons for non-renewal or cancellation and a copy will be sent to any known mortgagee. When cancellation is for nonpayment of premium, we will cancel this policy within the shortest notice period allowed by law.</p>
            </blockquote>
            <p>G. Earned Premium</p>
            <blockquote>
                <p>The total annual premium will be fully earned in the event of a claim which is likely to involve this policy or a loss which may be covered by this policy.</p>
            </blockquote>
            <p>H. Policy Renewal</p>
        </div>
    </div>
</div>

<!-- Page 23 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <blockquote>
                <p>
                    1. This policy will expire at 12:01 a.m. on the last day of the policy term, local standard time at the insured location.</p>
                <p>2. We may offer to renew this policy, at the premiums and under the policy provisions in effect on the date of renewal. If we elect to renew this policy, we will mail you a written Renewal Offer to the address shown in the Declarations Page, along with any changes in the policy provisions or amounts of coverage. If you reject our offer, this policy will automatically terminate at the end of the current policy period. Failure to pay the required renewal premium in full on or before the due date means you have rejected our offer.</p>
            </blockquote>
            <p>I. Conditions Suspending or Restricting Insurance</p>
            <blockquote>
                <p>We are not liable for loss that occurs while there is a hazard that is increased by any means within your control or knowledge.</p>
            </blockquote>
            <p>J. Requirements in Case of Loss</p>
            <blockquote>
                <p>In case of a Flood loss to insured property, you must:</p>
                <p>1. Give prompt written notice to us;</p>
                <p>2. As soon as reasonably possible, separate the damaged and undamaged property, putting it in the best possible order so that we may examine it;</p>
                <p>3. Prepare an inventory of damaged property showing the quantity, description, actual cash value, and amount of loss. Attach all bills, receipts, and related documents;</p>
                <p>4. Within 60 days after the loss, send us a proof of loss, which is your statement of the amount you are claiming under the policy signed and sworn to by you, and which furnishes us with the following information:</p>
                <blockquote>
                    <p>a. The date and time of loss;</p>
                    <p>b. A brief explanation of how the loss happened;</p>
                    <p>c. Your interest (for example, &quot;owner&quot;) and the interest, if any, of others in the damaged property;</p>
                    <p>d. Details of any other insurance that may cover the loss;</p>
                    <p>e. Changes in title or occupancy of the covered property during the term of the policy;</p>
                    <p>f. Specifications of damaged buildings and detailed repair estimates;</p>
                    <p>g. Names of mortgagees or anyone else having a lien, charge, or claim against the insured property;</p>
                    <p>h. Details about who occupied any insured building at the time of loss and for what purpose; and</p>
                    <p>i. The inventory of damaged personal property described in J.3. above.</p>
                </blockquote>
                <p>5. In completing the proof of loss, you must use your own judgment concerning the amount of loss and justify that amount.</p>
                <p>6. You must cooperate with the adjuster or representative in the investigation of the claim.</p>
                <p>7. The insurance adjuster whom we hire to investigate your claim may furnish you with a proof of loss form, and she or he may help you complete it. However, this is a matter of courtesy only, and you must still
                </p>
            </blockquote>
        </div>
    </div>
</div>

<!-- Page 24 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <blockquote>
                <p>send us a proof of loss within 60 days after the loss even if the adjuster does not furnish the form or help you complete it.</p>
                <p>8. We have not authorized the adjuster to approve or disapprove claims or to tell you whether we will approve your claim.</p>
                <p>9. At our option, we may accept the adjuster's report of the loss instead of your proof of loss. The adjuster's report will include information about your loss and the damages you sustained. You must sign the adjuster's report. At our option, we may require you to swear to the report.</p>
            </blockquote>
            <p>K. Our Options after a Loss</p>
            <blockquote>
                <p>Options we may, in our sole discretion, exercise after loss include the following:</p>
                <p>1. At such reasonable times and places that we may designate, you must:</p>
                <blockquote>
                    <p>a. Show us or our representative the damaged property;</p>
                    <p>b. Submit to examination under oath, while not in the presence of another insured, and sign the same; and</p>
                    <p>c. Permit us to examine and make extracts and copies of:</p>
                    <blockquote>
                        <p>(1) Any policies of property insurance insuring you against loss and the deed establishing your ownership of the insured real property;</p>
                        <p>(2) All books of accounts, bills, invoices and other vouchers, or certified copies pertaining to the damaged property if the originals are lost.</p>
                    </blockquote>
                </blockquote>
                <p>2. We may request, in writing, that you furnish us with a complete inventory of the lost, damaged, or destroyed property, including:</p>
                <blockquote>
                    <p>a. Quantities and costs;</p>
                    <p>b. Actual cash values or replacement cost (whichever is appropriate);</p>
                    <p>c. Amounts of loss claimed;</p>
                    <p>d. Any written plans and specifications for repair of the damaged property that you can reasonably make available to us; and</p>
                    <p>e. Evidence that prior flood damage has been repaired.</p>
                </blockquote>
                <p>3. If we give you written notice within 30 days after we receive your signed, sworn proof of loss, we may:</p>
                <blockquote>
                    <p>a. Repair, rebuild, or replace any part of the lost, damaged, or destroyed property with material or property of like kind and quality or its functional equivalent; and</p>
                    <p>b. Take all or any part of the damaged property at the value that we agree upon or its appraised value.</p>
                </blockquote>
            </blockquote>
            <p>L. No Benefit to Bailee</p>
            <blockquote>
                <p>No person or organization, other than you, having custody of covered property will benefit from this insurance.</p>
            </blockquote>
            <p>M. Loss Payment</p>
        </div>
    </div>
</div>

<!-- Page 25 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <blockquote>
                <p>1. We will adjust all losses with you. We will pay you unless some other person or entity is named in the policy or is legally entitled to receive payment. Loss will be payable 60 days after we receive your proof of loss (or within 90 days after the insurance adjuster files the adjuster’s report signed and sworn to by you in lieu of a proof of loss) and:</p>
                <blockquote>
                    <p>a. We reach an agreement with you;</p>
                    <p>b. There is an entry of a final judgment; or</p>
                    <p>c. There is a filing of an appraisal award with us, as provided in VII.P.</p>
                </blockquote>
                <p>2. If we reject your proof of loss in whole or in part you may:</p>
                <blockquote>
                    <p>a. Accept our denial of your claim;</p>
                    <p>b. Exercise your rights under this policy; or</p>
                    <p>c. File an amended proof of loss, as long as it is filed within 60 days of the date of the loss.</p>
                </blockquote>
            </blockquote>
            <p>N. Abandonment</p>
            <blockquote>
                <p>You may not abandon to us damaged or undamaged property insured under this policy.</p>
            </blockquote>
            <p>O. Salvage</p>
            <blockquote>
                <p>We may permit you to keep damaged property insured under this policy after a loss, and we will reduce the amount of the loss proceeds payable to you under the policy by the value of the salvage.</p>
            </blockquote>
            <p>P. Appraisal</p>
            <blockquote>
                <p>If you and we fail to agree on the actual cash value or, if applicable, replacement cost of your damaged property to settle upon the amount of loss, then either may demand an appraisal of the loss. In this event, you and we will each choose a competent and impartial appraiser within 20 days after receiving a written request from the other. The two appraisers will choose an umpire. If they cannot agree upon an umpire within 15 days, you or we may request that the choice be made by a judge of a court of record in the State where the covered property is located. The appraisers will separately state the actual cash value, the replacement cost, and the amount of loss to each item. If the appraisers submit a written report of an agreement to us, the amount agreed upon will be the amount of loss. If they fail to agree, they will submit their differences to the umpire. A decision agreed to by any two will set the amount of actual cash value and loss, or if it applies, the replacement cost and loss.</p>
                <p>Each party will:</p>
                <p>1. Pay its own appraiser; and</p>
                <p>2. Bear the other expenses of the appraisal and umpire equally.</p>
            </blockquote>
            <p>Q. Mortgage Clause</p>
            <blockquote>
                <p>The word &quot;mortgagee&quot; includes trustee.</p>
                <p>Any loss payable under Coverage A - Building Property will be paid to any mortgagee of whom we have actual notice as well as any other mortgagee or loss payee determined to exist at the time of loss, and you, as interests appear. If more than one mortgagee is named, the order of payment will be the same as the order of precedence of the mortgages.</p>
                <p>If we deny your claim, the denial will not apply to a valid claim of the mortgagee, if the mortgagee:</p>
            </blockquote>
        </div>
    </div>
</div>

<!-- Page 26 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <blockquote>
                <p>1. Notifies us of any change in the ownership or occupancy, or substantial change in risk of which the mortgagee is aware;</p>
                <p>2. Pays any premium due under this policy on demand if you have neglected to pay the premium; and</p>
                <p>3. Submits a signed, sworn proof of loss within 60 days after receiving notice from us of your failure to do so.</p>
                <p>All of the terms of this policy apply to the mortgagee.</p>
                <p>The mortgagee has the right to receive loss payment even if the mortgagee has started foreclosure or similar action on the building.</p>
                <p>If we decide to cancel or non-renew this policy, we will notify the mortgagee at least 45 days before the cancellation or non-renewal takes effect.</p>
                <p>If we pay the mortgagee for any loss and deny payment to you, we are subrogated to all the rights of the mortgagee granted under the mortgage on the property. Subrogation will not impair the right of the mortgagee to recover the full amount of the mortgagee's claim.</p>
            </blockquote>
            <p>R. Suit Against Us</p>
            <blockquote>
                <p>You may not sue us to recover money under this policy unless you have complied with all the requirements of the policy. If you do sue, you must start the suit within 1 year after the date of the written denial of all or part of the claim, and you must file the suit in the United States District Court of the district in which the covered property was located at the time of loss. This requirement applies to any claim that you may have under this policy and to any dispute that you may have arising out of the handling of any claim under the policy.</p>
            </blockquote>
            <p>S. Subrogation</p>
            <blockquote>
                <p>Whenever we make a payment for a loss under this policy, we are subrogated to your right to recover for that loss from any other person. That means that your right to recover for a loss that was partly or totally caused by someone else is automatically transferred to us, to the extent that we have paid you for the loss. We may require you to acknowledge this transfer in writing. After the loss, you may not give up our right to recover this money or do anything that would prevent us from recovering it. If you make any claim against any person who caused your loss and recover any money, you must pay us back first before you may keep any of that money.</p>
            </blockquote>
            <p>T. Continuous Lake Flooding</p>
            <blockquote>
                <p>1. If an insured building has been flooded by rising lake waters continuously for 90 days or more and it appears reasonably certain that a continuation of this flooding will result in a covered loss to the insured building equal to or greater than the building policy limits plus the deductible or the maximum payable under the policy for any one building loss, we will pay you the lesser of these two amounts without waiting for the further damage to occur if you sign a release agreeing:</p>
                <blockquote>
                    <p>a. To make no further claim under this policy;</p>
                    <p>b. Not to seek renewal of this policy;</p>
                    <p>c. Not to apply for any flood insurance underwritten by us for property at the described location; and</p>
                    <p>d. Not to seek a premium refund for current or prior terms.</p>
                </blockquote>
            </blockquote>
        </div>
    </div>
</div>

<!-- Page 27 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <blockquote>
                <blockquote>
                    <p>If the policy term ends before the insured building has been flooded continuously for 90 days, the provisions of this paragraph T.1. will apply when the insured building suffers a covered loss before the policy term ends.</p>
                </blockquote>
                <p>2. If your insured building is subject to continuous lake flooding from a closed basin lake, you may elect to file a claim under either paragraph T.1. above or T.2. (A &quot;closed basin lake&quot; is a natural lake from which water leaves primarily through evaporation and whose surface area now exceeds or has exceeded 1 square mile at any time in the recorded past. Most of the nation's closed basin lakes are in the western half of the United States, where annual evaporation exceeds annual precipitation and where lake levels and surface areas are subject to considerable fluctuation due to wide variations in the climate. These lakes may overtop their basins on rare occasions.) Under this paragraph T.2. we will pay your claim as if the building is a total loss even though it has not been continuously inundated for 90 days, subject to the following conditions:</p>
                <blockquote>
                    <p>a. Lake flood waters must damage or imminently threaten to damage your building.</p>
                    <p>b. Before approval of your claim, you must:</p>
                    <blockquote>
                        <p>(1) Agree to a claim payment that reflects your buying back the salvage on a negotiated basis;</p>
                        <p>(2) Grant the conservation easement described in FEMA’s “Policy Guidance for Closed Basin Lakes” to be recorded in the office of the local recorder of deeds. FEMA, in consultation with the community in which the property is located, will identify on a map an area or areas of special consideration (ASC) in which there is a potential for flood damage from continuous lake flooding. FEMA will give the community the agreed-upon map showing the ASC. This easement will only apply to that portion of the property in the ASC. It will allow certain agricultural and recreational uses of the land. The only structures it will allow on any portion of the property within the ASC are certain simple agricultural and recreational structures. If any of these allowable structures are insurable buildings under this policy and are insured under this policy, they will not be eligible for the benefits of this paragraph T.2. If a U.S. Army Corps of Engineers certified flood control project or otherwise certified flood control project later protects the property, FEMA will, upon request, amend the ASC to remove areas protected by those projects. The restrictions of the easement will then no longer apply to any portion of the property removed from the ASC; and-upon map showing the ASC.</p>
                        <p>(3) Comply with paragraphs T.1.a. through T.1.d above</p>
                    </blockquote>
                    <p>c. Within 90 days of approval of your claim, you must move your building to a new location outside the ASC. You will receive an additional 30 days to move if you show there is sufficient reason to extend the time.</p>
                    <p>d. Before the approval of your claim, the community having jurisdiction over your building must:</p>
                    <blockquote>
                        <p>(1) Adopt a permanent land use ordinance, or a temporary moratorium for a period not to exceed 6 months to be followed immediately by a permanent land use ordinance that is consistent with the provisions specified in the easement required in paragraph T.2.b. above.</p>
                        <p>(2) Agree to declare and report any violations of this ordinance to FEMA so that, under Section 1316 of the Act, flood insurance to the building can be denied; and</p>
                        <p>(3) Agree to maintain as deed-restricted, for purposes compatible with open space or agricultural or recreational use only, any affected property the community acquires an interest in. These deed restrictions must be consistent with the provisions of paragraph T.2.b. above, except that, even if a certified project protects the property, the land use restrictions continue to apply if the property was acquired under the Hazard Mitigation Grant Program or the Flood Mitigation Assistance Program. If a non-profit land trust organization receives the property as a donation, that organization must maintain the property as deed-restricted, consistent with the provisions of paragraph T.2.b. above.</p>
                    </blockquote>
                </blockquote>
            </blockquote>
        </div>
    </div>
</div>

<!-- Page 28 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <blockquote>
                <blockquote>
                    <p>e. Before the approval of your claim, the affected State must take all action set forth in FEMA's &quot;Policy Guidance for Closed Basin Lakes.&quot;</p>
                    <p>f. For the purpose of honoring a claim under this paragraph T.2., we will not consider to be in effect any increased coverage that became effective after the date established by FEMA. The exception to this is any increased coverage in the amount suggested by your insurer as an inflation adjustment.</p>
                    <p>g. This paragraph T.2. will be in effect for a community when the FEMA Regional Administrator for the affected region provides to the community, in writing, the following:</p>
                    <blockquote>
                        <p>(1) Confirmation that the community and the State are in compliance with the conditions in paragraphs T.2.e. and T.2.f. above; and</p>
                        <p>(2) The date by which you must have flood insurance in effect.</p>
                    </blockquote>
                </blockquote>
            </blockquote>
            <p>U. Loss Settlement</p>
            <blockquote>
                <p>1. Introduction</p>
                <blockquote>
                    <p>This policy provides two methods of settling losses: Replacement Cost and Actual Cash Value. Each method is explained below.</p>
                    <p>a. Replacement Cost loss settlement, described in V.2. below, applies to covered property that meets the definition of a dwelling.</p>
                    <p>b. Actual Cash Value loss settlement applies to covered property not subject to replacement cost settlement and to the property listed in V.3. below.</p>
                </blockquote>
                <p>2. Replacement Cost Loss Settlement</p>
                <blockquote>
                    <p>The following loss settlement conditions apply to covered property described in V.1.a. above:</p>
                    <p>a. We will pay to repair or replace the damaged covered property after application of the deductible and without deduction for depreciation, but not more than the least of the following amounts:</p>
                    <blockquote>
                        <p>(1) The limit of liability applicable to the damaged covered property, as shown on your Declarations Page;</p>
                        <p>(2) The replacement cost of that part of the damaged covered property, with materials of like kind and quality, and for like use; or</p>
                        <p>(3) The necessary amount actually spent to repair or replace the damaged part of the covered property for like use.</p>
                    </blockquote>
                    <p>b. If the dwelling is rebuilt at a new location, the cost described above is limited to the cost that would have been incurred if the dwelling had been rebuilt at its former location.</p>
                    <p>c. When the full cost of repair or replacement is more than $1,000 or more than 5 percent of the whole amount of insurance that applies to the dwelling, we will not be liable for any loss under V.2.a., above, or V.3.a., below, unless and until actual repair or replacement is completed.</p>
                    <p>d. You may disregard the replacement cost conditions above and make claim under this policy for loss to any covered property on an actual cash value basis. You may then make claim for any additional liability according to V.2.a., b., and c. above, provided you notify us of your intent to do so within 180 days after the date of loss.</p>
                </blockquote>
                <p>3. Actua
        </div>
    </div>
</div>

<!-- Page 29 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <blockquote>
                <blockquote>
                    <p>The types of property noted below are subject to actual cash value loss settlement.</p>
                    <p>a. We will pay the actual cash value of the damaged part of the following types of property, until such time as you actually repair or replace the damaged covered property, with materials of like kind and quality, and for like use, but not more than the amount of insurance that applies to that property:</p>
                    <blockquote>
                        <p>(a) Detached garages and other permanent structures, appurtenant to the dwelling.</p>
                        <p>(b) Personal property contained within the dwelling.</p>
                        <p>(c) Appliances, carpets, and carpet pads installed within the dwelling.</p>
                        <p>Within 180 days of our actual cash value payment to you, you may make a claim for reimbursement of the additional cost you actually spend to repair or replace the damaged covered property, with materials of like kind and quality, and for like use. You must present verifiable payment receipts for each item of covered property.</p>
                    </blockquote>
                    <p>b. Outdoor awnings, outdoor antennas or aerials of any type, and other outdoor equipment.</p>
                    <p>c. Any property covered under this policy that is abandoned after a loss and remains as debris anywhere on the described location.</p>
                </blockquote>
            </blockquote>
            <p>V. Duplicate Policies Not Allowed</p>
            <blockquote>
                <p>1. We will not insure your property under more than one policy.</p>
                <p>If we find that the duplication was not knowingly created, we will give you written notice. The notice will advise you that you may choose one of several options under the following procedures:</p>
                <p>a. If you choose to keep in effect the policy with the earlier effective date, you may also choose to add the coverage limits of the later policy to the limits of the earlier policy. The change will become effective as of the effective date of the later policy.</p>
                <p>b. If you choose to keep in effect the policy with the later effective date, you may also choose to add the coverage limits of the earlier policy to the limits of the later policy. The change will be effective as of the effective date of the later policy.</p>
                <p>In either case, you must pay the pro rata premium for the increased coverage limits within 30 days of the written notice. In no event will the resulting coverage limits exceed our permissible limits of coverage or your insurable interest, whichever is less.</p>
                <p>We will make a refund to you, of the premium for the policy not being kept in effect.</p>
                <p>2. Your option under Condition U. Duplicate Policies Not Allowed to elect which policy to keep in effect does not apply when duplicates have been knowingly created. Losses occurring under such circumstances will be adjusted according to the terms and conditions of the earlier policy. The policy with the later effective date must be canceled.</p>
            </blockquote>
        </div>
    </div>
</div>

<!-- Page 30 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>CLAUSES</strong></p>

            <p class="text-center"><strong>SANCTION LIMITATION AND EXCLUSION CLAUSE</strong></p>
            <p>No (re)insurer shall be deemed to provide cover and no (re)insurer shall be liable to pay any claim or provide any benefit hereunder to the extent that the provision of such cover, payment of such claim or provision of such benefit would expose that (re)insurer to any sanction, prohibition or restriction under United Nations resolutions or the trade or economic sanctions, laws or regulations of the European Union, United Kingdom or United States of America.</p>
            <p>15/09/10<br />
                LMA3100</p>
            <p class="text-center"><strong>LLOYD'S PRIVACY POLICY STATEMENT</strong><br />
                UNDERWRITERS AT LLOYD'S, LONDON</p>
            <p>The Certain Underwriters at Lloyd's, London want you to know how we protect the confidentiality of your non-public personal information. We want you to know how and why we use and disclose the information that we have about you. The following describes our policies and practices for securing the privacy of our current and former customers.</p>
            <p>INFORMATION WE COLLECT</p>
            <p>The non-public personal information that we collect about you includes, but is not limited to:</p>
            <ul>
                <li>Information contained in applications or other forms that you submit to us, such as name, address, and social security number</li>
                <li>Information about your transactions with our affiliates or other third-parties, such as balances and payment history</li>
            </ul>
        </div>
    </div>
</div>

<!-- Page 31 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <ul>
                <li>Information we receive from a consumer-reporting agency, such as credit-worthiness or credit history</li>
            </ul>
            <p>INFORMATION WE DISCLOSE</p>
            <p>We disclose the information that we have when it is necessary to provide our products and services. We may also disclose information when the law requires or permits us to do so.</p>
            <p>CONFIDENTIALITY AND SECURITY</p>
            <p>Only our employees and others who need the information to service your account have access to your personal information. We have measures in place to secure our paper files and computer systems.</p>
            <p class="text-center"><strong>RIGHT TO ACCESS OR CORRECT YOUR PERSONAL INFORMATION</strong></p>
            <p><strong>You have a right to request access to or correction of your personal information that is in our possession.</strong></p>
            <p><strong>CONTACTING US</strong></p>
            <p>If you have any questions about this privacy notice or would like to learn more about how we protect your privacy, please contact the agent or broker who handled this insurance. We can provide a more detailed statement of our privacy practices upon request.</p>
            <p>06/03<br />
                LSW1135B</p>
            <p class="text-center"><strong>LIBERALISATION CLAUSE</strong></p>
            <p>If the NFIP makes a change that broadens your coverage under this edition of our policy, but does not require any additional premium, then that change will automatically apply to your insurance as of the date the NFIP underwriters implement the change, provided that this implementation date falls within 60 days before, or during the policy term stated on the Declarations Page.</p>
            <p>Where the policy limits exceed NFIP maximum available limits, this policy is not intended to comply or conform to NFIP standards and the provisions within stand as written.</p>
            <p>LIBUSV12020e001</p>
            <p class="text-center"><strong>SEVERAL LIABILITY NOTICE</strong></p>
            <p>The subscribing insurers' obligations under contracts of insurance to which they subscribe are several and not joint and are limited solely to the extent of their individual subscriptions. The subscribing insurers are not responsible for the subscription of any co-subscribing insurer who for any reason does not satisfy all or part of its obligations.</p>
            <p>08/94<br />
                LSW1001 (Insurance)</p>
        </div>
    </div>
</div>

<!-- Page 32 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>APPLICABLE LAW (U.S.A.)</strong></p>
            <p>This Insurance shall be subject to the applicable state law to be determined by the court of competent jurisdiction as determined by the provisions of the Service of Suit Clause (U.S.A.) </p>
            <p>&nbsp;</p>
            <p>LMA5021<br />
                14/09/2005</p>
        </div>
    </div>
</div>

<!-- Page 33 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>SERVICE OF SUIT CLAUSE (U.S.A.)</strong></p>
            <p>This Service of Suit Clause will not be read to conflict with or override the obligations of the parties to arbitrate their disputes as provided for in any Arbitration provision within this Policy.  This Clause is intended as an aid to compelling arbitration or enforcing such arbitration or arbitral award, not as an alternative to such Arbitration provision for resolving disputes arising out of this contract of insurance (or reinsurance). </p>
            <p>It is agreed that in the event of the failure of the Underwriters hereon to pay any amount claimed to be due hereunder, the Underwriters hereon, at the request of the Insured (or Reinsured), will submit to the jurisdiction of a Court of competent jurisdiction within the United States.  Nothing in this Clause constitutes or should be understood to constitute a waiver of Underwriters' rights to commence an action in any Court of competent jurisdiction in the United States, to remove an action to a United States District Court, or to seek a transfer of a case to another Court as permitted by the laws of the United States or of any State in the United States.</p>
            <p>It is further agreed that service of process in such suit may be made upon </p>
            <p>Lloyd's America, Inc.<br />
                Attention: Legal department <br />
                280 Park Avenue, East Tower, 25th Floor<br />
                New York, NY 10017</p>
            <p>and that in any suit instituted against any one of them upon this contract, Underwriters will abide by the final decision of such Court or of any Appellate Court in the event of an appeal.</p>
            <p>The above-named are authorized and directed to accept service of process on behalf of Underwriters in any such suit and/or upon the request of the Insured (or Reinsured) to give a written undertaking to the Insured (or Reinsured) that they will enter a general appearance upon Underwriters' behalf in the event such a suit shall be instituted.</p>
            <p>Further, pursuant to any statute of any state, territory or district of the United States which makes provision therefor, Underwriters hereon hereby designate the Superintendent, Commissioner or Director of Insurance or other officer specified for that purpose in the statute, or his successor or successors in office, as their true and lawful attorney upon whom may be served any lawful process in any action, suit or proceeding instituted by or on behalf of the Insured (or Reinsured) or any beneficiary hereunder arising out of this contract of insurance (or reinsurance), and hereby designate the above-named as the person to whom the said officer is authorized to mail such process or a true copy thereof.</p>
            <p>LMA5020<br />
                14/09/2005</p>
        </div>
    </div>
</div>

<!-- Page 34 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center">&nbsp;</p>
            <p class="text-center"><strong>LLOYD'S PRIVACY POLICY STATEMENT </strong></p>
            <p><strong>UNDERWRITERS AT LLOYD'S, LONDON</strong></p>
            <p>The Certain Underwriters at Lloyd's, London want you to know how we protect the confidentiality of your non-public personal information.  We want you to know how and why we use and disclose the information that we have about you.  The following describes our policies and practices for securing the privacy of our current and former customers. </p>
            <p><strong>INFORMATION WE COLLECT</strong></p>
            <p>The non-public personal information that we collect about you includes, but is not limited to:</p>
            <ul>
                <li>Information contained in applications or other forms that you submit to us, such as name, address, and social security number </li>
                <li>Information about your transactions with our affiliates or other third-parties, such as balances and payment history</li>
                <li>Information we receive from a consumer-reporting agency, such as credit-worthiness or credit history</li>
            </ul>
            <p><strong>INFORMATION WE DISCLOSE</strong></p>
            <p>We disclose the information that we have when it is necessary to provide our products and services.  We may also disclose information when the law requires or permits us to do so.</p>
            <p><strong>CONFIDENTIALITY AND SECURITY</strong></p>
            <p>Only our employees and others who need the information to service your account have access to your personal information.  We have measures in place to secure our paper files and computer systems.  </p>
            <p><strong><u>RIGHT TO ACCESS OR CORRECT YOUR PERSONAL INFORMATION</u></strong></p>
            <p><strong>You have a right to request access to or correction of your personal information that is in our possession. </strong></p>
            <p><strong>CONTACTING US </strong></p>
            <p>If you have any questions about this privacy notice or would like to learn more about how we protect your privacy, please contact the agent or broker who handled this insurance.  We can provide a more detailed statement of our privacy practices upon request.</p>
            <p>06/03<br />
                LSW1135B</p>
        </div>
    </div>
</div>

<!-- Page 35 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>SANCTION LIMITATION AND EXCLUSION CLAUSE</strong><br />
                <strong> </strong>
            </p>
            <p>No (re)insurer shall be deemed to provide cover and no (re)insurer shall be liable to pay any claim or provide any benefit hereunder to the extent that the provision of such cover, payment of such claim or provision of such benefit would expose that (re)insurer to any sanction, prohibition or restriction under United Nations resolutions or the trade or economic sanctions, laws or regulations of the European Union, United Kingdom or United States of America.</p>
            <p>&nbsp;</p>
            <p>LMA3100<br />
                15 September 2010 </p>
        </div>
    </div>
</div>

<!-- Page 36 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>FRAUDULENT CLAIM CLAUSE</strong><br />
            </p>
            <p>If the (re)insured shall make any claim knowing the same to be false or fraudulent, as regards amount or otherwise, this contract shall become void and all claim hereunder shall be forfeited.</p>
            <p>LMA5062<br />
                4 September 2006</p>
        </div>
    </div>
</div>

<!-- Page 37 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>WAR AND TERRORISM EXCLUSION ENDORSEMENT</strong> </p>
            <p>
                Notwithstanding any provision to the contrary within this insurance or any endorsement thereto it is agreed that this insurance excludes loss, damage, cost or expense of whatsoever nature directly or indirectly caused by, resulting from or in connection with any of the following regardless of any other cause or event contributing concurrently or in any other sequence to the loss; <br />
            </p>
            <p>(1)        war, invasion, acts of foreign enemies, hostilities or warlike operations (whether war be declared or not), civil war, rebellion, revolution, insurrection, civil commotion assuming the proportions of or amounting to an uprising, military or usurped power; or <br />
            </p>
            <p>(2)        any act of terrorism. <br />
                For the purpose of this endorsement an act of terrorism means an act, including but not limited to the use of force or violence and/or the threat thereof, of any person or group(s) of persons, whether acting alone or on behalf of or in connection with any organisation(s) or government(s), committed for political, religious, ideological or similar purposes including the intention to influence any government and/or to put the public, or any section of the public, in fear. </p>
            <p> This endorsement also excludes loss, damage, cost or expense of whatsoever nature directly or indirectly caused by, resulting from or in connection with any action taken in controlling, preventing, suppressing or in any way relating to (1) and/or (2) above. </p>
            <p> If the Underwriters allege that by reason of this exclusion, any loss, damage, cost or expense is not covered by this insurance the burden of proving the contrary shall be upon the Assured.</p>
            <p> In the event any portion of this endorsement is found to be invalid or unenforceable, the remainder shall remain in full force and effect.</p>
            <p>&nbsp;</p>
            <p>NMA2918<br />
                08/10/2001</p>
        </div>
    </div>
</div>

<!-- Page 38 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p><strong>WAR AND CIVIL WAR EXCLUSION CLAUSE</strong></p>
            <p>Notwithstanding anything to the contrary contained herein this Policy does not cover Loss or Damage directly or indirectly occasioned by, happening through or in consequence of war, invasion, acts of foreign enemies, hostilities (whether war be declared or not), civil war, rebellion, revolution, insurrection, military or usurped power or confiscation or nationalisation or requisition or destruction of or damage to property by or under the order of any government or public or local authority.</p>
            <p>N.M.A. 464</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>All other terms and conditions remain unaltered</p>
        </div>
    </div>
</div>

<!-- Page 39 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>SEEPAGE &amp; POLLUTION, LAND, AIR WATER EXCLUSION &amp; DEBRIS REMOVAL ENDORSEMENT</strong></p>
            <p>LAND, WATER AND AIR EXCLUSION<br />
                Notwithstanding any provision to the contrary within the Policy of which this Endorsement forms part (or within any other Endorsement which forms part of this Policy), this Policy does not insure land (including but not limited to land on which the insured property is located), water or air, howsoever and wherever occurring, or any interest or right therein.</p>
            <p>SEEPAGE AND/OR POLLUTION AND/OR CONTAMINATION EXCLUSION</p>
            <p>Notwithstanding any provision to the contrary within the Policy of which this Endorsement forms part (or within any other Endorsement which forms part of this Policy), this Policy does not insure:</p>
            <p>(a)  any loss, damage, cost or expense, or</p>
            <p>(b)  any increase in insured loss, damage, cost or expense, or</p>
            <p>(c)  any loss, damage, cost, expense, fine or penalty, which is incurred, sustained or imposed by order, direction, instruction or request of, or by any agreement with, any court, government agency or any public, civil or military authority, or threat thereof, (and whether or not as a result of public or private litigation),</p>
            <p>which arises from any kind of seepage or any kind of pollution and/or contamination, or threat thereof, whether or not caused by or resulting from a peril insured, or from steps or measures taken in connection with the avoidance, prevention, abatement, mitigation, remediation, clean-up or removal of such seepage or pollution and/or contamination or threat thereof.</p>
            <p>The term &quot;any kind of seepage or any kind of pollution and/or contamination&quot; as used in this Endorsement includes (but is not limited to):</p>
            <p>(a)  seepage of, or pollution and/or contamination by, anything, including but not limited to, any material designated as a &quot;hazardous substance&quot; by the United States Environmental Protection Agency or as a &quot;hazardous material&quot; by the United States Department of Transportation, or defined as a &quot;toxic substance&quot; by the Canadian Environmental Protection Act for the purposes of Part II of that Act, or any substance designated or defined as toxic, dangerous, hazardous or deleterious to persons or the environment under any other Federal, State, Provincial, Municipal or other law, ordinance or regulation; and</p>
            <p>(b)  the presence, existence, or release of anything which endangers or threatens to endanger the health, safety or welfare of persons or the environment.</p>
            <p>DEBRIS REMOVAL ENDORSEMENT</p>
            <p>
                THIS ENDORSEMENT CONTAINS PROVISIONS WHICH MAY LIMIT OR PREVENT RECOVERY UNDER THIS POLICY FOR LOSS WHERE COSTS OR EXPENSES FOR DEBRIS REMOVAL ARE INCURRED.</p>
            <p>Nothing contained in this Endorsement shall override any Seepage and/or Pollution and/or Contamination Exclusion or any Radioactive Contamination Exclusion or any other Exclusion applicable to this Policy.</p>
            <p>Any provision within this Policy (or within any other Endorsement which forms part of this Policy) which insures debris removal is cancelled and replaced by the following:</p>
        </div>
    </div>
</div>

<!-- Page 40 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p>1.   In the event of direct physical damage to or destruction of property, for which Underwriters hereon agree to pay, or which but for the application of a deductible or underlying amount they would agree to pay (hereinafter referred to as &quot;Damage or Destruction&quot;), this Policy also insures, within the Sum Insured, subject to the limitations and method of calculation below, and to all the other terms and conditions of the Policy, costs or expenses;
            </p>
            <blockquote>
                <p>(a)  which are reasonably and necessarily incurred by the Assured in the removal, from the premises of the Assured at which the Damage or Destruction occurred, of debris which results from the Damage or Destruction; and</p>
                <p>(b)  of which the Assured becomes aware and advises the amount thereof to Underwriters hereon within one year of the commencement of such Damage or Destruction.</p>
            </blockquote>
            <p>2.   In calculating the amount, if any, payable under this Policy for loss  where costs or expenses for removal of debris are incurred by the Assured (subject to the limitations in paragraph 1 above):</p>
            <blockquote>
                <p>(a)  the maximum amount of such costs or expenses that can be included in the method of calculation set out in (b) below shall be the greater of US$25,000 (twenty-five thousand dollars) or 10% (ten percent) of the amount of the Damage or Destruction from which such costs or expenses result; and</p>
                <p>(b)  the amount of such costs or expenses as limited in (a) above shall be added to:</p>
                <blockquote>
                    <p>(i)   the amount of the Damage or Destruction; and</p>
                    <p>(ii)   all other amounts of loss, which arise as a result of the same occurrence, and for which Underwriters hereon also agree to pay, or which but for the application of a deductible or underlying amount they would agree to pay; and</p>
                    <p>the resulting sum shall be the amount to which any deductible or underlying amount to which this Policy is subject and the limit (or applicable sub-limit) of this Policy, shall be applied.</p>
                </blockquote>
            </blockquote>
            <p>&nbsp;</p>
            <p>NMA2340<br />
                24/11/1988</p>
        </div>
    </div>
</div>

<!-- Page 41 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>RADIOACTIVE CONTAMINATION EXCLUSION CLAUSE -<br />
                    PHYSICAL DAMAGE - DIRECT</strong></p>
            <p>This policy does not cover any loss or damage arising directly or indirectly from nuclear reaction nuclear radiation or radioactive contamination however such nuclear reaction nuclear radiation or radioactive contamination may have been caused * NEVERTHELESS if Fire is an insured peril and a Fire arises directly or indirectly from nuclear reaction nuclear radiation or radioactive contamination any loss or damage arising directly from that Fire shall (subject to the provisions of this policy) be covered EXCLUDING however all loss or damage caused by nuclear reaction nuclear radiation or radioactive contamination arising directly or indirectly from that Fire.</p>
            <p>* NOTE. - If Fire is not an insured peril under this policy the words &quot;NEVERTHELESS&quot; to the end of the clause do not apply and should be disregarded.</p>
            <p>&nbsp;</p>
            <p>NMA1191<br />
                07/05/1959</p>
        </div>
    </div>
</div>

<!-- Page 42 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p><strong>ASBESTOS ENDORSEMENT</strong></p>
            <blockquote>
                <p>A. This Policy only insures asbestos physically incorporated in an insured building or structure, and then only that part of the asbestos which has been physically damaged during the period of insurance by one of these Listed Perils:</a></p>
                <p>Flood</p>
            </blockquote>
            <p>This coverage is subject to each of the following specific limitations:</p>
            <ol>
                <li>The said building or structure must be insured under this Policy for damage by that Listed Peril.</li>
                <li>The Listed Peril must be the immediate, sole cause of the damage of the asbestos.</li>
                <li>The Assured must report to Underwriters the existence and cost of the damage as soon as practicable after the Listed Peril first damaged the asbestos.  However, this Policy does not insure any such damage first reported to the Underwriters more than 12 (twelve) months after the expiration, or termination, of the period of insurance.</li>
                <li>Insurance under this Policy in respect of asbestos shall not include any sum relating to:</li>
                <ol>
                    <ul>
                        <li>i. any faults in the design, manufacture or installation of the asbestos;</li>
                        <li>ii. asbestos not physically damaged by the Listed Peril including any governmental or regulatory authority direction or request of whatsoever nature relating to undamaged asbestos.</li>
                    </ul>
                </ol>
            </ol>
            <blockquote>
                <p>B. Except as set forth in the foregoing Section A, this Policy does not insure asbestos or any sum relating thereto.&nbsp;</p>
            </blockquote>
            <p>&nbsp;</p>
            <p>LMA5019 (amended)<br />
                14/09/2005</p>
        </div>
    </div>
</div>

<!-- Page 43 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>MICROORGANISM EXCLUSION (Absolute) </strong></p>
            <p>This Policy does not insure any loss, damage, claim, cost, expense or other sum directly or indirectly arising out of or relating to:</p>
            <blockquote>
                <p>mold, mildew, fungus, spores or other microorganism of any type, nature, or description, including but not limited to any substance whose presence poses an actual or potential threat to human health.</p>
            </blockquote>
            <p>This Exclusion applies regardless whether there is (i) any physical loss or damage to insured property; (ii) any insured peril or cause, whether or not contributing concurrently or in any sequence; (iii) any loss of use, occupancy, or functionality; or (iv) any action required, including but not limited to repair, replacement, removal, cleanup, abatement, disposal, relocation, or steps taken to address medical or legal concerns.</p>
            <p>This Exclusion replaces and supersedes any provision in the Policy that provides insurance, in whole or in part, for these matters.</p>
            <p>&nbsp;</p>
            <p>LMA5018<br />
                14/09/2005</p>
        </div>
    </div>
</div>

<!-- Page 44 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>VALUES LIMITATION CLAUSE</strong></p>
            <p>The premium for this Policy is based upon the schedule of values reported to and on file with the Underwriters, or attached to this Policy. In the event of any covered loss under this Policy, the liability of the Underwriters relative to property damage and time element loss, as insured by this Policy, shall, notwithstanding anything contained herein to the contrary, be limited to the least of the following:</p>
            <p>(a)        The actual adjusted amount of the loss within the coverage of the Policy, less applicable deductible(s).</p>
            <p>(b)        (1)        for property damage loss,           % of the total property values for each location</p>
            <blockquote>
                <p>(2)        for time element loss, as insured by this Policy,            % of the time element values for each location</p>
                <p>as reported on the above said schedule of values, less applicable deductible(s).</p>
            </blockquote>
            <p>(c)        The Policy limit of liability or applicable sub-limit(s) of liability, less applicable deductible(s).</p>
            <p>All other terms and conditions remain unchanged.</p>
            <p>&nbsp;</p>
            <p>LMA5060<br />
                15/03/2006</p>
        </div>
    </div>
</div>

<!-- Page 45 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>BIOLOGICAL OR CHEMICAL MATERIALS EXCLUSION</strong></p>
            <p>It is agreed that this Insurance excludes loss, damage, cost or expense of whatsoever nature directly or indirectly caused by, resulting from or in connection with the actual or threatened malicious use of pathogenic or poisonous biological or chemical materials regardless of any other cause or event contributing concurrently or in any other sequence thereto.</p>
            <p>&nbsp;</p>
            <p>NMA2962<br />
                06/02/2003</p>
        </div>
    </div>
</div>

<!-- Page 46 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>CANCELLATION CLAUSE</strong></p>
            <p>NOTWITHSTANDING anything contained in this Insurance to the contrary this Insurance may be cancelled by the Assured at any time by written notice or by surrendering of this contract of insurance.  This Insurance may also be cancelled by or on behalf of the Underwriters by delivering to the Assured or by mailing to the Assured, by registered, certified or other first class mail, at the Assured's address as shown in this Insurance, written notice stating when, not less than ……………             days thereafter, the cancellation shall be effective.  The mailing of notice as aforesaid shall be sufficient proof of notice and this Insurance shall terminate at the date and hour specified in such notice.</p>
            <p>If this Insurance shall be cancelled by the Assured the Underwriters shall retain the customary short rate proportion of the premium hereon, except that if this Insurance is on an adjustable basis the Underwriters shall receive the earned premium hereon or the customary short rate proportion of any minimum premium stipulated herein whichever is the greater.</p>
            <p>If this Insurance shall be cancelled by or on behalf of the Underwriters the Underwriters shall retain the pro rata proportion of the premium hereon, except that if this Insurance is on an adjustable basis the Underwriters shall receive the earned premium hereon or the pro rata proportion of any minimum premium stipulated herein whichever is the greater.</p>
            <p>Payment or tender of any unearned premium by the Underwriters shall not be a condition precedent to the effectiveness of Cancellation but such payment shall be made as soon as practicable.</p>
            <p>If the period of limitation relating to the giving of notice is prohibited or made void by any law controlling the construction thereof, such period shall be deemed to be amended so as to be equal to the minimum period of limitation permitted by such law.</p>
            <p>&nbsp;</p>
            <p>NMA 1331<br />
                20/04/1961</p>
        </div>
    </div>
</div>

<!-- Page 47 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>PROPERTY CYBER AND DATA EXCLUSION</strong></p>

            1 Notwithstanding any provision to the contrary within this Policy or any endorsement thereto this Policy excludes any:
            <ol>
                <blockquote>
                    1.1 Cyber Loss;<br>
                    1.2 loss, damage, liability, claim, cost, expense of whatsoever nature directly or indirectly caused by, contributed to by, resulting from, arising out of or in connection with any loss of use, reduction in functionality, repair, replacement, restoration or reproduction  of any Data, including any amount pertaining to the value of such Data; <strong><u></u></strong></p>
                    <br>
                    regardless of any other cause or event contributing concurrently or in any other sequence thereto.
                </blockquote>
            </ol>
            2 In the event any portion of this endorsement is found to be invalid or unenforceable, the remainder shall remain in full force and effect.<br>
            3 This endorsement supersedes and, if in conflict with any other wording in the Policy or any endorsement thereto having a bearing on Cyber Loss or Data, replaces that wording.
            <strong><u>Definitions</u></strong><br>
            4 Cyber Loss means any loss, damage, liability, claim, cost or expense of whatsoever nature directly or indirectly caused by, contributed to by, resulting from, arising out of or in connection with any Cyber Act or Cyber Incident including, but not limited to, any action taken in controlling, preventing, suppressing or remediating any Cyber Act or Cyber Incident.<br>
            5 Cyber Act means an unauthorised, malicious or criminal act or series of related unauthorised, malicious or criminal acts, regardless of time and place, or the threat or hoax thereof involving access to, processing of, use of or operation of any Computer System.<br>
            6 Cyber Incident means: <br>
            6.1 any error or omission or series of related errors or omissions involving access to, processing of, use of or operation of any Computer System; or <br>
            6.2 any partial or total unavailability or failure or series of related partial or total unavailability or failures to access, process, use or operate any Computer System.<br>
            7 Computer System means:<br>
            <blockquote> 7.1 any computer, hardware, software, communications system, electronic device (including, but not limited to, smart phone, laptop, tablet, wearable device), server, cloud or microcontroller including any similar system or any configuration of the aforementioned and including any associated input, output, data storage device, networking equipment or back up facility,
                <br>owned or operated by the Insured or any other party.<br>
            </blockquote>
            8 Data means information, facts, concepts, code or any other information of any kind that is recorded or transmitted in a form to be used, accessed, processed, transmitted or stored by a Computer System.
            <p>LMA5401<br />
                11 November 2019</p>
        </div>
    </div>
</div>

<!-- Page 48 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>ELECTRONIC DATE RECOGNITION EXCLUSION (EDRE)</strong></p>
            <p>This policy does not cover any loss, damage, cost, claim or expense, whether preventative, remedial or otherwise, directly or indirectly arising out of or relating to:</p>
            <p>a)         the calculation, comparison, differentiation, sequencing or processing of data involving the date change to the year 2000, or any other date change, including leap year calculations, by any computer system, hardware, programme or software and/or any microchip, integrated circuit or similar device in computer equipment or non-computer equipment, whether the property of the insured or not; or</p>
            <p>b)         any change, alteration, or modification involving the date change to the year 2000, or any other date change, including leap year calculations, to any such computer system, hardware, programme or software and/or any microchip, integrated circuit or similar device in computer equipment or non-computer equipment, whether the property of the insured or not.</p>
            <p>This clause applies regardless of any other cause or event that contributes concurrently or in any sequence to the loss, damage, cost, claim or expense.</p>
            <p><strong>EDRE</strong><br />
                NMA2802  <br />
                17/12/1997</p>
        </div>
    </div>
</div>

<!-- Page 49 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>COMMUNICABLE DISEASE ENDORSEMENT<br />
                    (For use on property policies)</strong></p>
            <ol>
                <li>This policy, subject to all applicable terms, conditions and exclusions, covers losses attributable to direct physical loss or physical damage occurring during the period of insurance. Consequently and notwithstanding any other provision of this policy to the contrary, this policy does not insure any loss, damage, claim, cost, expense or other sum, directly or indirectly arising out of, attributable to, or occurring concurrently or in any sequence with a Communicable Disease or the fear or threat (whether actual or perceived) of a Communicable Disease. </a></li>
                <li>For the purposes of this endorsement, loss, damage, claim, cost, expense or other sum, includes, but is not limited to, any cost to clean-up, detoxify, remove, monitor or test: </li>
                <ol>
                    <li>for a Communicable Disease,<strong> </strong>or </li>
                    <li>any property<strong> </strong>insured hereunder that is affected by such Communicable Disease.</li>
                </ol>
                <li>As used herein, a Communicable Disease</a> means any disease which can be transmitted by means of any substance or agent from any organism to another organism where:</li>
                <ol>
                    <li>the substance or agent includes, but is not limited to, a virus, bacterium, parasite or other organism or any variation thereof, whether deemed living or not, and</li>
                    <li>the method of transmission, whether direct or indirect, includes but is not limited to, airborne transmission, bodily fluid transmission, transmission from or to any surface or object, solid, liquid or gas or between organisms, and</li>
                    <li>the disease, substance or agent can cause or threaten damage to human health or human welfare or can cause or threaten damage to, deterioration of, loss of value of, marketability of or loss of use of property insured hereunder.</li>
                </ol>
            </ol>
            <ol>
                <li>This endorsement applies to all coverage extensions, additional coverages, exceptions to any exclusion and other coverage grant(s).&nbsp;<strong>&nbsp;</strong></li>
            </ol>
            <p class="text-center"><strong>All other terms, conditions and exclusions of the policy remain the same.</strong></a></p>
            <p><strong>&nbsp;</strong></p>
            <p>LMA5393<br />
                25 March 2020</p>
        </div>
    </div>
</div>

<!-- Page 50 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>USA FLOOD MINIMUM EARNED PREMIUM ENDORSEMENT</strong></p>
            <p>The following terms and conditions will apply to this policy where the peril of Flood in included:</p>
            <blockquote>
                <p>1. If you cancel this policy, remove a location or reduce the amount of Insurance on a location that is within 75 miles of the Atlantic Ocean and/or the Gulf of Mexico in the states of North Carolina thru to Texas inclusive, and coverage existed any time during the period of June 1st to November 1st, the amount of premium we will return will be the Unearned Premium for the location. The Unearned Premium is the annual premium for the policy (or for the location removed or coverage reduced, as applicable) multiplied by the Unearned Factor noted below. The location premium is the 100% annual rate multiplied by the location value as scheduled in the most current Statement of Values on file with Underwriters.</p>
            </blockquote>
            <p>&nbsp;</p>
            <p class="text-center">1 year Policy</p>
            <blockquote>
                <p>Days Policy<br />
                    In Force&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Unearned Factor</p>
                <p>001 to&nbsp; 180&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 25%<br />
                    181 to&nbsp; 210&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 20%<br />
                    211 to&nbsp; 240&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 15%<br />
                    241 to&nbsp; 270&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 10%<br />
                    271 to&nbsp; 300&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 5.0%<br />
                    301 to&nbsp; 330&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2.5%<br />
                    331 to&nbsp; 365&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 0.0%</p>
            </blockquote>
            <p>&nbsp;</p>
            <blockquote>
                <p>2. The provisions of this endorsement replace any short rate provisions stipulated in this policy for all locations that are within 75 miles of the Atlantic Ocean and/or the Gulf of Mexico in the states of North Carolina thru to Texas inclusive, and coverage existed any time during the period of June 1st to November 1st.</p>
            </blockquote>
            <p>All other terms and conditions remain unchanged</p>
            <p>NMA0045<br />
                31/05/1948</p>
        </div>
    </div>
</div>

<!-- Page 51 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>EXISTING DAMAGE EXCLUSION ENDORSEMENT</strong></p>
            <p class="text-center"><strong>APPLIES TO ALL FORMS</strong></p>
            <blockquote>
                <p>            <br />
                    <strong>EXCLUSIONS</strong><br />
                    The following exclusion is added.
                </p>
            </blockquote>
            <p>&nbsp;</p>
            <p>            <strong>Existing Damage</strong><br />
                We do not insure for loss caused directly or indirectly by existing damage. Such loss is excluded regardless of any other cause or event contributing concurrently or in any sequence to the loss. These exclusions apply whether or not the loss event results in widespread damage or affects a substantial area.</p>
            <p>Existing Damage means:</p>
            <p>A. Any damages which occurred prior to policy inception regardless of whether such damages were apparent at the time of the inception of this policy or at a later date;</p>
            <p>B. Any claims or damages arising out of workmanship, repairs and or lack of repairs arising from damage which occurred prior to policy inception; or</p>
            <p>C. Any claims or damages unless all structures covered by your previous policy have been fully and completely repaired. Prior to such completion of repairs, coverage will be limited to the actual cash value of the property at the time of a covered loss occurring during the policy period,</p>
            <p>&nbsp;</p>
            <p>All other provisions of this policy apply. </p>
            <p>All other terms and conditions remain unchanged<br />
                EDEUSV12020e001</p>
        </div>
    </div>
</div>

<!-- Page 53 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center">BRIT <strong>REPLACEMENT COST ENDORSEMENT </strong><strong>(amended) </strong></p>
            <p>In consideration of the premium paid for this Insurance references to &ldquo;Actual Cash Value&rdquo; in the wording to which this Endorsement applies are deleted and substituted with &ldquo;Replacement Cost&rdquo; except where the insured property is listed on any Historical Register where references to &ldquo;Actual Cash Value&rdquo; in the wording to which the Endorsement applies are deleted and substituted with &ldquo;Functional Replacement Cost Loss Settlement&rdquo;.</p>
            <p class="text-center"><strong><u>REPLACEMENT COST</u></strong><strong> </strong></p>
            <p class="text-center">Replacement cost is subject to the following provisions:-<strong></strong></p>
            <p>a)         Any settlement shall be based on whichever is the least of the cost of repairing, replacing or reinstating the destroyed or damaged property with material of like kind and quality;</p>
            <p>b)         The repair, replacement or reinstatement (all hereinafter referred to as &ldquo;replacement&rdquo;) shall be intended for the same occupancy as the destroyed or damaged property;</p>
            <p>c)         The replacement must be executed with due diligence and dispatch;</p>
            <p>d)         Until replacement has been effected the amount of liability under this Policy in respect of loss shall be limited to the actual cash value at the time of loss;</p>
            <p>e)         If replacement with material of like kind and quality is restricted or prohibited by any by‑laws, ordinance or law, any increased cost of replacement due thereto shall not be covered by this Endorsement.</p>
            <p>The Underwriters&rsquo; liability for loss under this Policy, including this Endorsement, shall not exceed the smallest of the following amounts:-</p>
            <blockquote>
                <p>i) the amount of the Policy applicable to the destroyed or damaged property, or </p>
                <p>ii) the replacement cost of the property or any part thereof identical with such property and intended for the same occupancy and use, or</p>
                <p>iii) the amount actually and necessarily expended in replacing said property or any part thereof.</p>
            </blockquote>
            <p> If the property is rebuilt at a new location, the cost described above shall not exceed the cost that would have been incurred if the property had been rebuilt at its former location.</p>
        </div>
    </div>
</div>

<!-- Page 54 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong><u>FUNCTIONAL REPLACEMENT COST LOSS SETTLEMENT</u></strong></p>
            <p class="text-center">Functional Replacement cost is subject to the following provisions:-<strong></strong>
            </p>
            <p>a)         Any settlement shall be based on whichever is the least of the cost of repairing, replacing or reinstating the destroyed or damaged property with less costly common construction materials and methods which are functionally equivalent to obsolete, antique or custom construction materials and methods used in the original construction of the building;</p>
            <p>b)         The repair, replacement or reinstatement (all hereinafter referred to as &ldquo;replacement&rdquo;) shall be intended for the same occupancy as the destroyed or damaged property;</p>
            <p>c)         The replacement must be executed with due diligence and dispatch;</p>
            <p>d)         Until replacement has been effected the amount of liability under this Policy in respect of loss shall be limited to the actual cash value at the time of loss;</p>
            <p>e)         If replacement with functionally equivalent material is restricted or prohibited by any by‑laws, ordinance or law, any increased cost of replacement due thereto shall not be covered by this Endorsement.<br />
                The Underwriters&rsquo; liability for loss under this Policy, including this Endorsement, shall not exceed the smallest of the following amounts:-</p>
            <blockquote>
                <p>i) the amount of the Policy applicable to the destroyed or damaged property, or</p>
                <p>ii) the functional replacement cost of the property or any part thereof identical with such property and intended for the same occupancy and use, or</p>
                <p>iii) the amount actually and necessarily expended in replacing said property or any part thereof.</p>
            </blockquote>
            <p>If the property is rebuilt at a new location, the cost described above shall not exceed the cost that would have been incurred if the property had been rebuilt at its former location.</p>
            <p>All other Terms, Clauses and Conditions remain unaltered.</p>
            <p>&nbsp;</p>
            <p>LMA 5038 (amended)<br />
                14/12/2005</p>
        </div>
    </div>
</div>

<!-- Page 55 -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <p class="text-center"><strong>CO-INSURANCE CLAUSE</strong></p>
            <p>It is hereby made a condition of this Policy that the Assured shall at all times maintain Insurance equivalent to {Response} % of the {Replacement Cost or Actual Cash Value} of the property insured hereby, and that failing so to do shall be his or her own insurer to the extent of any deficit and bear a ratable proportion of any loss accordingly<strong> </strong></p>
            <p><strong>&nbsp;</strong></p>
            <p>LII91 -  (03/07)</p>
        </div>
    </div>
</div>


<?php if ($boundLossUseCoverage > 0): ?>
    <!-- Page 56 -->
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12">
                <p class="text-center">THIS ENDORSEMENT CHANGES THE POLICY PLEASE READ IT CAREFULLY</p>
                <p class="text-center"><strong>LOSS OF USE ENDORSEMENT</strong><strong> </strong><br />
                    The following Coverage is added to this policy:<br />
                    <strong>LOSS OF USE</strong> <br />
                    The limit of liability for Loss of Use is the total limit for the coverages in <strong>1) Additional Living Expenses</strong> and <strong>2) Fair Rental Value</strong> below.
                </p>
                <ol>
                    <li><strong>Additional Living Expenses</strong><br>If a direct physical loss by or from Flood makes that part of the dwelling at the described location not fit to live in, we cover any necessary expenses incurred by you so that your household can maintain its normal standard of living.</li>

                    <li><strong>Fair Rental Value</strong><br>If a direct physical loss by or from Flood makes that part of the dwelling at the described location rented to others or held for rental by you not fit to live in, we cover fair rental value of such premises less any expenses that do not continue while it is not fit to live in.<br />
                    </li>
                </ol>
                <blockquote>
                    <p>We will only pay for that part of the loss that exceeds the deductible amount shown in the Declarations.</p>
                </blockquote>
                <p>This coverage is subject to the following conditions, limitations and exclusions:</p>
                <ol>
                    <li>We will pay you for the shortest time required to repair or replace that damaged portion of the dwelling that made it unsafe or in poor condition to live in.<br /><br />

                    </li>
                    <li>This coverage shall continue (even if the coverage that applies to the described location expires after the date of loss) until the repair and/or replacement of the damaged portion of the insured dwelling is completed.<br /><br />
                    </li>
                    <li>This coverage does not apply to loss or expense due to the cancellation of a lease or agreement.<br /><br />
                    </li>
                    <li>We will not pay more than USD [response] any one occurrence and in the annual aggregate. </li>
                </ol>
                <p>All other terms and conditions remain unaltered</p>
                <p>LOUUSV12020e001</p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>