<?php

use Illuminate\Database\Seeder;
use App\Channel;
use App\CommentSummary;
use App\CommentType;

class CommentSummaryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $channel_service=Channel::firstWhere('title', 'Service')->id;
        $channel_parts=Channel::firstWhere('title', 'Parts')->id;
        $channel_sales=Channel::firstWhere('title', 'Sales')->id;
        $channel_body_shop=Channel::firstWhere('title', 'Body Shop')->id;

        //Comment Types
        $type_negative=CommentType::firstWhere('title', 'Negative')->id;
        $type_positive=CommentType::firstWhere('title', 'Positive')->id;
        $type_suggestion=CommentType::firstWhere('title', 'Suggestion')->id;
        $type_enquiries=CommentType::firstWhere('title', 'Enquiries')->id;
        $type_neutral=CommentType::firstWhere('title', 'Neutral')->id;

        //Service
        $services = array(
            'New Additional Work After Visit (Lead) - (Service - Negative)' =>$type_negative,
            'Account/payment issue - (Service - Negative)' =>$type_negative,
            "Can't get Appointment - (Service - Negative)" =>$type_negative,
            'Delayed repair: Long diagnosis - (Service - Negative)' =>$type_negative,
            'Delayed: Parts not in stock - (Service - Negative)' =>$type_negative,
            'Delayed: Promised time not met - (Service - Negative)' =>$type_negative,
            "Facility - (Service - Negative)" =>$type_negative,
            'FIR: Poor Maintenance quality - (Service - Negative)' =>$type_negative,
            'FIR: Poor repair quality - (Service - Negative)' =>$type_negative,
            'Invoice/Payment takes too long - (Service - Negative)' =>$type_negative,
            "Not Cleaned Sufficiently - (Service - Negative)" =>$type_negative,
            'Not all requested work completed - (Service - Negative)' =>$type_negative,
            'Poor Communication - (Service - Negative)' =>$type_negative,
            'Poor explanation of work done - (Service - Negative)' =>$type_negative,
            'Poor handling/attitude - (Service - Negative)' =>$type_negative,
            'Quotation took too long - (Service - Negative)' =>$type_negative,
            'Refuse to repair - (Service - Negative)' =>$type_negative,
            'Security / Item missing - (Service - Negative)' =>$type_negative,
            'Too Expensive - (Service - Negative)' =>$type_negative,
            'Unauthorized repair - (Service - Negative)' =>$type_negative,
            'Unrepaired: Parts not in stock - (Service - Negative)' =>$type_negative,
            'Unrepaired: Repeat Repair not successful - (Service - Negative)' =>$type_negative,
            'Vehicle was at Service Centre Longtime - (Service - Negative)' =>$type_negative,
            'Vehicle was damaged - (Service - Negative)' =>$type_negative,
            'Waited too long to drop off/collect - (Service - Negative)' =>$type_negative,
            'Warranty: Issue with Process - (Service - Negative)'  =>$type_negative,
            'Gift/promotional item - (Service - Negative)' =>$type_negative,
            'Other - (Service - Negative)' =>$type_negative,

            'Good Customer Care - (Service - Positive)'=>$type_positive,
            'Good Quality of Work - (Service - Positive)'=>$type_positive,
            'SA knowledge - (Service - Positive)'=>$type_positive,
            'Good Communication - (Service - Positive)'=>$type_positive,
            'Good Leadtime - (Service - Positive)'=>$type_positive,
            'Good Pricing - (Service - Positive)'=>$type_positive,
            'Good Vehicle Delivery - (Service - Positive)'=>$type_positive,
            'Facility/Location - (Service - Positive)'=>$type_positive,
            'Other - (Service - Positive)'=>$type_positive,

            "Owner's Manual request - (Service - Enquiries)"=> $type_enquiries,
            "Repair Manual request - (Service - Enquiries)"=> $type_enquiries,
            "Repair/maintenance service - (Service - Enquiries)"=> $type_enquiries,
            "Conversion - (Service - Enquiries)"=> $type_enquiries,
            "Technical information, new technology/mechanism - (Service - Enquiries)"=> $type_enquiries,
            "Special Service Campaign, recall - (Service - Enquiries)"=> $type_enquiries,
            "Labour price - (Service - Enquiries)"=> $type_enquiries,
            "Parts delivery time/availability - (Service - Enquiries)"=> $type_enquiries,
            "Dealer information (Service) - (Service - Enquiries)"=> $type_enquiries,
            "Paperwork & other certificate - (Service - Enquiries)"=> $type_enquiries,
            "Suggestions - (Service - Enquiries)"=> $type_enquiries,
            "Other - (Service - Enquiries)"=> $type_enquiries,

            "Owner's Manual request - (Service - Suggestion)"=>$type_suggestion,
            "Repair Manual request - (Service - Suggestion)"=>$type_suggestion,
            "Repair/maintenance service - (Service - Suggestion)"=>$type_suggestion,
            "Conversion - (Service - Suggestion)"=>$type_suggestion,
            "Technical information, new technology/mechanism - (Service - Suggestion)"=>$type_suggestion,
            "Special Service Campaign, recall - (Service - Suggestion)"=>$type_suggestion,
            "Labour price - (Service - Suggestion)"=>$type_suggestion,
            "Parts delivery time/availability - (Service - Suggestion)"=>$type_suggestion,
            "Dealer information (Service) - (Service - Suggestion)"=>$type_suggestion,
            "Paperwork & other certificate - (Service - Suggestion)"=>$type_suggestion,
            "Suggestions - (Service - Suggestion)"=>$type_suggestion,
            "Other - (Service - Suggestion)"=>$type_suggestion,

            "Not Applicable  - (Service - Neutral)" =>$type_neutral

        );

        foreach ($services as $service => $code) {
            CommentSummary::query()->create([
                'comment_type_id' => $code,
                'channel_id' =>  $channel_service,
                'comment_summary' => $service
            ]);
        }



        //parts
        $parts = array(
            'Delay: Parts not in Stock  - (Parts - Negative)' =>$type_negative,
            'Too Expensive  - (Parts - Negative)' =>$type_negative,
            'Incorrect parts Supplied  - (Parts - Negative)' =>$type_negative,
            'Invoice/Payment takes too long  - (Parts - Negative)' =>$type_negative,
            'Part not Available  - (Parts - Negative)' =>$type_negative,
            'Parts Warranty: issue with Process  - (Parts - Negative)' =>$type_negative,
            'Parts: Facility  - (Parts - Negative)' =>$type_negative,
            'Poor Communication  - (Parts - Negative)' =>$type_negative,
            'Poor handling/attitude  - (Parts - Negative)' =>$type_negative,
            'Poor Quality of Parts  - (Parts - Negative)' =>$type_negative,
            'Promised time not met  - (Parts - Negative)' =>$type_negative,
            'Quotation took too long  - (Parts - Negative)' =>$type_negative,
            'Supply condition of spare parts  - (Parts - Negative)' =>$type_negative,
            'Waited too long to be helped  - (Parts - Negative)' =>$type_negative,
            'Whole Process Took too long  - (Parts - Negative)' =>$type_negative,
            'Other - (Parts - Negative)' =>$type_negative,

            'Good Customer Care - (Parts - Positive)'=>$type_positive,
            'Good Parts Availability - (Parts - Positive)'=>$type_positive,
            'Sales Person knowledge - (Parts - Positive)'=>$type_positive,
            'Good Communication - (Parts - Positive)'=>$type_positive,
            'Good Leadtime - (Parts - Positive)'=>$type_positive,
            'Good Pricing - (Parts - Positive)'=>$type_positive,
            'Good Parts Process - (Parts - Positive)'=>$type_positive,
            'Good Quality of Parts - (Parts - Positive)'=>$type_positive,
            'Other - (Parts - Positive)'=>$type_positive,

            "Technical information, new technology/mechanism - (Parts - Enquiries)"=> $type_enquiries,
            "Part price - (Parts - Enquiries)"=> $type_enquiries,
            "Parts delivery time/availability - (Parts - Enquiries)"=> $type_enquiries,
            "Parts Catalogue - (Parts - Enquiries)"=> $type_enquiries,
            "Parts number - (Parts - Enquiries)"=> $type_enquiries,
            "Dealer information (Parts) - (Parts - Enquiries)"=> $type_enquiries,
            "Paperwork & other certificate - (Parts - Enquiries)"=> $type_enquiries,
            "Suggestions - (Parts - Enquiries)"=> $type_enquiries,
            "Other - (Parts - Enquiries)"=> $type_enquiries,

            "Technical information, new technology/mechanism - (Parts - Suggestion)"=>$type_suggestion,
            "Part price - (Parts - Suggestion)"=>$type_suggestion,
            "Parts delivery time/availability - (Parts - Suggestion)"=>$type_suggestion,
            "Parts Catalogue - (Parts - Suggestion)"=>$type_suggestion,
            "Parts number - (Parts - Suggestion)"=>$type_suggestion,
            "Dealer information (Parts) - (Parts - Suggestion)"=>$type_suggestion,
            "Paperwork & other certificate - (Parts - Suggestion)"=>$type_suggestion,
            "Suggestions - (Parts - Suggestion)"=>$type_suggestion,
            "Other - (Parts - Suggestion)"=>$type_suggestion,

            "Not Applicable - (Parts - Neutral)"=>$type_neutral

        );

        foreach ( $parts as  $part => $code) {
            CommentSummary::query()->create([
                'comment_type_id' => $code,
                'channel_id' =>$channel_parts,
                'comment_summary' =>  $part
            ]);
        }

        //end parts

        //sales
        $sales = array(
            'Additional accessory not fitted - (Sales - Negative)' =>$type_negative,
            'Quality Issue [Accessory] - (Sales - Negative)' =>$type_negative,
            'Catalogues, publicity, advertising - (Sales - Negative)' =>$type_negative,
            'Displeasure with contract - (Sales - Negative)' =>$type_negative,
            'Finance, insurance - (Sales - Negative)' =>$type_negative,
            'Late delivery - (Sales - Negative)' =>$type_negative,
            'Not Cleaned Sufficiently - (Sales - Negative)' =>$type_negative,
            'Poor Communication - (Sales - Negative)' =>$type_negative,
            'Poor explanation at delivery - (Sales - Negative)' =>$type_negative,
            'Poor handling / Attitude - (Sales - Negative)' =>$type_negative,
            'Poor vehicle condition on delivery (Damage) - (Sales - Negative)' =>$type_negative,
            'Registration procedures - (Sales)' =>$type_negative,
            'Requested colour Not Available - (Sales - Negative)' =>$type_negative,
            'Requested variant Not Available- (Sales - Negative)' =>$type_negative,
            'Security / Item missing - (Sales - Negative)' =>$type_negative,
            'Too Expensive - (Sales - Negative)' =>$type_negative,
            'Too many additional cost - (Sales - Negative)' =>$type_negative,
            'Waited a long time for the vehicle - (Sales - Negative)' =>$type_negative,
            'Wrong product/option delivered - (Sales - Negative)' =>$type_negative,
            'Not happy with Gift - (Sales - Negative)' =>$type_negative,
            'Issue after delivery (Re-classify)- (Sales - Negative)' =>$type_negative,
            'Other - (Sales - Negative)' =>$type_negative,

            'Good Customer care (Sales - Positive)'=>$type_positive,
            'Sales Process - (Sales - Positive)'=>$type_positive,
            'Sales Person knowledge - (Sales - Positive)'=>$type_positive,
            'Good Communication - (Sales - Positive)'=>$type_positive,
            'Good Leadtime - (Sales - Positive)'=>$type_positive,
            'Good Pricing - (Sales - Positive)'=>$type_positive,
            'Good Vehicle handover - (Sales - Positive)'=>$type_positive,
            'Other (Sales - Positive)'=>$type_positive,
       

            "Distributor/dealer information - (Sales - Enquiries)"=> $type_enquiries,
            "Catalogue - (Sales - Enquiries)"=> $type_enquiries,
            "Sales/Lease contract - (Sales - Enquiries)"=> $type_enquiries,
            "Purchase - (Sales - Enquiries)"=> $type_enquiries,
            "Finance, insurance - (Sales - Enquiries)"=> $type_enquiries,
            "Publicity, advertising - (Sales - Enquiries)"=> $type_enquiries,
            "New model introduction - (Sales - Enquiries)"=> $type_enquiries,
            "Rent-A-Car/lease information - (Sales - Enquiries)"=> $type_enquiries,
            "Bring vehicle in/out of country - (Sales - Enquiries)"=> $type_enquiries,
            "Sales promotion - (Sales - Enquiries)"=> $type_enquiries,
            "Suggestions - (Sales - Enquiries)"=> $type_enquiries,
            "Other - (Sales - Enquiries)"=> $type_enquiries,

            "Distributor/dealer information - (Sales - Suggestion)"=>$type_suggestion,
            "Catalogue - (Sales - Suggestion)"=>$type_suggestion,
            "Sales/Lease contract - (Sales - Suggestion)"=>$type_suggestion,
            "Purchase - (Sales - Suggestion)"=>$type_suggestion,
            "Finance, insurance - (Sales - Suggestion)"=>$type_suggestion,
            "Publicity, advertising - (Sales - Suggestion)"=>$type_suggestion,
            "New model introduction - (Sales - Suggestion)"=>$type_suggestion,
            "Rent-A-Car/lease information - (Sales - Suggestion)"=>$type_suggestion,
            "Bring vehicle in/out of country - (Sales - Suggestion)"=>$type_suggestion,
            "Sales promotion - (Sales - Suggestion)"=>$type_suggestion,
            "Suggestions - (Sales - Suggestion)"=>$type_suggestion,
            "Other - (Sales - Suggestion)"=>$type_suggestion,

            "Not Applicable - (Sales - Neutral)"=>$type_neutral

        );

        foreach ( $sales as  $sale=> $code) {
            CommentSummary::query()->create([
                'comment_type_id' => $code,
                'channel_id' =>$channel_sales,
                'comment_summary' =>  $sale
            ]);
        }

        //end sales

        //Body Shop
        $bodyshops = array(
            "Can't get Appointment - (Bodyshop - Negative)" =>$type_negative,
            "New Additional Work After Visit (Lead) - (Bodyshop - Negative)" =>$type_negative,
            "Refuse to repair - (Bodyshop - Negative)" =>$type_negative,
            "Repeat Repair, still unrepaired - (Bodyshop - Negative)" =>$type_negative,
            "FIR: Poor repair quality - (Bodyshop - Negative)" =>$type_negative,
            "Delayed repair - (Bodyshop - Negative)" =>$type_negative,
            "Poor explanation of work done - (Bodyshop - Negative)" =>$type_negative,
            "Too Expensive - (Bodyshop - Negative)" =>$type_negative,
            "Vehicle was at BP Longtime - (Bodyshop - Negative)" =>$type_negative,
            "Delayed: Parts not in stock - (Bodyshop - Negative)" =>$type_negative,
            "Expensive spare parts - (Bodyshop - Negative)" =>$type_negative,
            "Poor handling/attitude - (Bodyshop - Negative)" =>$type_negative,
            "Warranty - (Bodyshop - Negative)" =>$type_negative,
            "Not Cleaned Sufficiently - (Bodyshop - Negative)" =>$type_negative,
            "Quotation took too long - (Bodyshop - Negative)" =>$type_negative,
            "Waited too long to drop off/collect - (Bodyshop - Negative)" =>$type_negative,
            "Poor Communication - (Bodyshop - Negative)" =>$type_negative,
            "Vehicle was damaged - (Bodyshop - Negative)" =>$type_negative,
            "Security / Item missing - (Bodyshop - Negative)" =>$type_negative,
            "Facility - (Bodyshop - Negative)" =>$type_negative,
            "Other - (Bodyshop - Negative)" =>$type_negative,
    

            'Good Customer Care - (Bodyshop - Positive)'=>$type_positive,
            'Good Quality of Work - (Bodyshop - Positive)'=>$type_positive,
            'SA knowledge - (Bodyshop - Positive)'=>$type_positive,
            'Good Communication - (Bodyshop - Positive)'=>$type_positive,
            'Good Leadtime - (Bodyshop - Positive)'=>$type_positive,
            'Good Pricing - (Bodyshop - Positive)'=>$type_positive,
            'Good Vehicle Delivery - (Bodyshop - Positive)'=>$type_positive,
            'Other  - (Bodyshop - Positive)'=>$type_positive,
    
       
            "Owner's Manual request  - (Bodyshop - Enquiries)"=> $type_enquiries,
            "Repair Manual request  - (Bodyshop - Enquiries)"=> $type_enquiries,
            "Repair/maintenance service  - (Bodyshop - Enquiries)"=> $type_enquiries,
            "Conversion  - (Bodyshop - Enquiries)"=> $type_enquiries,
            "Technical information, new technology/mechanism  - (Bodyshop - Enquiries)"=> $type_enquiries,
            "Special Service Campaign, recall  - (Bodyshop - Enquiries)"=> $type_enquiries,
            "Labour price  - (Bodyshop - Enquiries)"=> $type_enquiries,
            "Parts delivery time/availability  - (Bodyshop - Enquiries)"=> $type_enquiries,
            "Dealer information (Service)  - (Bodyshop - Enquiries)"=> $type_enquiries,
            "Paperwork & other certificate  - (Bodyshop - Enquiries)"=> $type_enquiries,
            "Suggestions  - (Bodyshop - Enquiries)"=> $type_enquiries,
            "Other - (Bodyshop - Enquiries)"=> $type_enquiries,

            "Owner's Manual request - (Bodyshop - Suggestion)"=>$type_suggestion,
            "Repair Manual request - (Bodyshop - Suggestion)"=>$type_suggestion,
            "Repair/maintenance service - (Bodyshop - Suggestion)"=>$type_suggestion,
            "Conversion - (Bodyshop - Suggestion)"=>$type_suggestion,
            "Technical information, new technology/mechanism - (Bodyshop - Suggestion)"=>$type_suggestion,
            "Special Service Campaign, recall - (Bodyshop - Suggestion)"=>$type_suggestion,
            "Labour price - (Bodyshop - Suggestion)"=>$type_suggestion,
            "Parts delivery time/availability - (Bodyshop - Suggestion)"=>$type_suggestion,
            "Dealer information (Service) - (Bodyshop - Suggestion)"=>$type_suggestion,
            "Paperwork & other certificate - (Bodyshop - Suggestion)"=>$type_suggestion,
            "Suggestions - (Bodyshop - Suggestion)"=>$type_suggestion,
            "Other - (Bodyshop - Suggestion)"=>$type_suggestion,

            "Not Applicable - (Bodyshop - Neutral)"=>$type_neutral

        );

        foreach ( $bodyshops  as  $bodyshop=> $code) {
            CommentSummary::query()->create([
                'comment_type_id' => $code,
                'channel_id' =>$channel_body_shop,
                'comment_summary' => $bodyshop
            ]);
        }

        //End Body Shop
    }
}
