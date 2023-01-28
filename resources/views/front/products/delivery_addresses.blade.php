@if(count($deliveryAddresses) > 0)
    <h4 class="section-h4">Delivery Addresses</h4>
    <!-- Form-Fields /- -->
    @foreach($deliveryAddresses as $address)
            <div class="control-group" style="float:left; margin-right:5px;"><input type="radio" name="address_id" id="address{{ $address['id'] }}" value="{{ $address['id'] }}">
            </div>
            <div><label class="control-label" for="">{{ $address['name'] }}, {{ $address['city'] }}, {{ $address['state'] }}, {{ $address['country'] }} ({{ $address['mobile'] }})</label>
                <a style="float: right; margin-left:10px" href="javascript:;" data-addressid="{{ $address['id'] }}" class="removeAddress">Remove</a>
                <a style="float: right;" href="javascript:;" data-addressid="{{ $address['id'] }}" class="editAddress">Edit</a>
            </div>
            <br>
    @endforeach
@endif
    <h4 class="section-h4 deliveryText">Add New Delivery Address</h4>
    <div class="u-s-m-b-24">
    <input type="checkbox" class="check-box" id="ship-to-different-address" data-toggle="collapse" data-target="#showdifferent">
        <label class="label-text newAddress" for="ship-to-different-address">Ship to a different address?</label>
    </div>
    <div class="collapse" id="showdifferent">
    <!-- Form-Fields -->
    <form id="addressAddEditForm" action="javascript:;" method="post">@csrf
        <input type="hidden" name="delivery_id">
        <div class="group-inline u-s-m-b-13">
            <div class="group-1 u-s-p-r-16">
                <label for="first-name-extra">Name
                    <span class="astk">*</span>
                </label>
                <input type="text" name="delivery_name" id="delivery_name" class="text-field">
            </div>
            <div class="group-2">
                <label for="last-name-extra">Address
                    <span class="astk">*</span>
                </label>
                <input type="text" name="delivery_address" id="delivery_address" class="text-field">
            </div>
        </div>
        <div class="group-inline u-s-m-b-13">
            <div class="group-1 u-s-p-r-16">
                <label for="first-name-extra">City
                    <span class="astk">*</span>
                </label>
                <input type="text" name="delivery_city" id="delivery_city" class="text-field">
            </div>
            <div class="group-2">
                <label for="last-name-extra">State
                    <span class="astk">*</span>
                </label>
                <input type="text" name="delivery_state" id="delivery_state" class="text-field">
            </div>
        </div>

        <div class="u-s-m-b-13">
            <label for="select-country-extra">Country
                <span class="astk">*</span>
            </label>
            <div class="select-box-wrapper">
                <select class="select-box" name="delivery_country" id="delivery_country">
                    <option value="">Select Country</option>
                    @foreach($countries as $country)
                        <option value="{{ $country['country_name'] }}" @if($country['country_name'] == Auth::user()->country) selected @endif> 
                            {{ $country['country_name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    
        <div class="u-s-m-b-13">
            <label for="postcode-extra">Pincode
                <span class="astk">*</span>
            </label>
            <input type="text" id="delivery_pincode" name="delivery_pincode" class="text-field">
        </div>
        <div class="u-s-m-b-13">
            <label for="postcode-extra">Mobile
                <span class="astk">*</span>
            </label>
            <input type="text" id="delivery_mobile" name="delivery_mobile" class="text-field">
        </div>
        <div class="u-s-m-b-13">
            <button style="width:100%" type="submit" class="button button-outline-secondary">Save</button>
        </div>
    </form>
    <!-- Form-Fields /- -->
    </div>
    <div>
    <label for="order-notes">Order Notes</label>
    <textarea class="text-area" id="order-notes" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
    </div>
