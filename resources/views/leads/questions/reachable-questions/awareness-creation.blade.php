<h5 class="mt-2"> Awareness Creation</h5>
<div class="form-group">
    <label >{{ !empty($contact->customer_description) ? $contact->customer_description : 'N/A' }}  allow me to take this time to update you on a few items</label>
    <div class="form-group">
        <label>Kindly confirm if you are aware of our Toyota Kenya Toll Free line</label> <br>
        <div class="form-check form-check-inline">
            <input 
            class="form-check-input"
            type="radio"
            name="awareness_creation_aware"
            id="awarenessCreationAwareYes"
            value="YES"
            required
            >
            <label class="form-check-label" for="awarenessCreationAwareYes">
                Yes
            </label>
        </div>
        <div class="form-check form-check-inline">
            <input 
                class="form-check-input"
                type="radio"
                name="awareness_creation_aware"
                id="awarenessCreationAwareNo"
                value="NO"
                required
            >
            <label class="form-check-label" for="awarenessCreationAwareNo">
                No
            </label>
        </div>
        
    </div>
    <div class="form-group">
        <label>Whenever you pay a visit to our offices, are you satisfied with the measures put in place for COVID 19 protocols?</label>
        <div class="form-group">
            <div class="form-check form-check-inline">
                <input 
                class="form-check-input"
                type="radio"
                name="awareness_creation_satisfaction"
                id="awarenessCreationSatisfactionYes"
                value="YES"
                required
                >
                <label class="form-check-label" for="awarenessCreationSatisfactionYes">
                    Yes
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input 
                    class="form-check-input"
                    type="radio"
                    name="awareness_creation_satisfaction"
                    id="awarenessCreationSatisfactionNo"
                    value="NO"
                    required
                >
                <label class="form-check-label" for="awarenessCreationSatisfactionNo">
                    No
                </label>
            </div>
            
        </div>
    </div>
    <div class="form-group">
        <label for="awarenessCreationComment">Comment</label>
        <input type="text" name="awareness_creation_comment" class="form-control" required>
    </div>
    <div class="form-group">
        <label>We are glad to notify you that in {{ $branchName}} you can call in to order parts and have them delivered to you.</label>
    </div>
</div>