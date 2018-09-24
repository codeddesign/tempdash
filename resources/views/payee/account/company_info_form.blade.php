<form v-on:submit="handleSubmit" id="user_company_information_form">
    <div v-cloak class="form-group required">
        <label>Company</label>
        <input v-model="company" v-bind:class="{'is-invalid': errors['company']}" class="form-control" type="text" name="company"/>
        <ul v-if="errors['company']" class="invalid-form-feedback">
            <li v-for="error in errors['company']">@{{ error }}</li>
        </ul>
    </div>
    <div v-cloak class="form-group required">
        <label>Phone Number</label>
        <input v-bind:class="{'is-invalid': errors['phone']}" placeholder="XXX-XXX-XXXX" v-model="phone" class="form-control" type="phone" name="phone"/>
        <ul v-if="errors['phone']" class="invalid-form-feedback">
            <li v-for="error in errors['phone']">@{{ error }}</li>
        </ul>
    </div>
    <div v-cloak class="form-group required">
        <label>Address Line 1</label>
        <input v-bind:class="{'is-invalid': errors['address_line_1']}" v-model="address_line_1" class="form-control" type="text" name="address_line_1"/>
        <ul v-if="errors['address_line_1']" class="invalid-form-feedback">
            <li v-for="error in errors['address_line_1']">@{{ error }}</li>
        </ul>
    </div>
    <div v-cloak class="form-group required">
        <label>Address Line 2</label>
        <input v-model="address_line_2" class="form-control" type="text" name="address_line_2"/>
    </div>
    <div v-cloak class="form-group required">
        <label>City</label>
        <input v-bind:class="{'is-invalid': errors['address_city']}" v-model="address_city" class="form-control" type="text" name="address_city"/>
        <ul v-if="errors['address_city']" class="invalid-form-feedback">
            <li v-for="error in errors['address_city']">@{{ error }}</li>
        </ul>
    </div>
    <div v-cloak class="form-group required">
        <label>State/Province</label>
        <select v-bind:class="{'is-invalid': errors['address_state']}" v-model="address_state" class="form-control" type="text" name="address_state">
            <option value="">Select Province/State</option>
            <option v-for="option in available_states" v-bind:value="option.short || option.name">@{{ option.name }}</option>
        </select>
        <ul v-if="errors['address_state']" class="invalid-form-feedback">
            <li v-for="error in errors['address_state']">@{{ error }}</li>
        </ul>
    </div>
    <div v-cloak class="form-group required">
        <label>Zip Code</label>
        <input v-bind:class="{'is-invalid': errors['address_zip']}" v-model="address_zip" class="form-control" type="text" name="address_zip"/>
        <ul v-if="errors['address_zip']" class="invalid-form-feedback">
            <li v-for="error in errors['address_zip']">@{{ error }}</li>
        </ul>
    </div>
    <div v-cloak class="form-group required">
        <label>Country</label>
        <select v-bind:class="{'is-invalid': errors['address_country']}" v-on:change="updateCountry" v-model="address_country" class="form-control" type="text" name="address_country">
            <option value="">Select Country</option>
            <option value="US">United States of America</option>
            <option value="CA">Canada</option>
        </select>
        <ul v-if="errors['address_country']" class="invalid-form-feedback">
            <li v-for="error in errors['address_country']">@{{ error }}</li>
        </ul>
    </div>
    <div v-cloak class="form-group required">
        <button v-bind:disabled="is_ajaxing" type="submit" class="btn btn-primary">
            @{{ is_ajaxing ? 'Please wait...' : 'Update Information' }}
        </button>
    </div>
</form>