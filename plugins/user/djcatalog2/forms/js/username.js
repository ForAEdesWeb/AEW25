var plguserdjc2 = {
	init: function() {
		this.target = document.id('jform_name');
		this.firstname = document.id('jform_djcatalog2profile_firstname');
		this.lastname = document.id('jform_djcatalog2profile_lastname');
		this.form1 = document.id('member-registration');
		this.form2 = document.id('member-profile');
		
		if (!this.target || !this.lastname || !this.firstname) {
			return;
		}
		instance = this;
		if (this.form1) {
			this.form1.addEvent('submit', this.setname.pass(instance));
		} else if (this.form2) {
			this.form2.addEvent('submit', this.setname.pass(instance));
		}
		
		
	},
	
	setname: function(instance) {
		instance.target.value = instance.firstname.value + ' ' + instance.lastname.value;
		return true;
	}
};