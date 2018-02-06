import accounting from 'accounting';

export default {
    methods: {
        validateRequired: function() {
            return function(v) {
                if (typeof v == "undefined") {return 'Dieses Feld ist erforderlich';}
                return (v.length > 0) || 'Dieses Feld ist erforderlich.';
            };
        },
        validateSelected: function() {
            return function(v) {
                if (typeof v == "undefined") {return 'Dieses Feld ist erforderlich';}
                return (parseInt(v) && v > 0) || 'Dieses Feld ist erforderlich.';
            };
        },
        validateMin: function(min) {
            return function(v) {
                if (typeof v == "undefined") {return 'Bitte mindestens '+min+' Zeichen eineben.';}
                return (v.length >= min) || 'Bitte mindestens '+min+' Zeichen eingeben.';
            };
        },
        validateEmail: function() {
            return function(v) {
                return /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(v) || 'Das hier ist keine richtige E-Mail-Adresse';
            };
        },
        validateCurrency: function() {
            return function(v) {
                return /^[0-9]+,[0-9]{2}$/.test(v) || 'Beiträge müssen im Format XXXX,XX angegeben werden.';
            }
        },
        //Can be pased directly in the catch callback of axios
        showErrors: function(res, error) {
            var errors = [];
            var vm = this;

            Object.keys(res.response.data).forEach(function(key) {
                errors.push('Fehler in Feld '+(vm.$refs[key] ? vm.$refs[key].$props.label : key)+': '+res.response.data[key]);
            });

            vm.$store.commit('errormsg', errors, 5000);
        },
        money: function(m) {
            return accounting.formatMoney(m / 100, '€', 2, ",", ",", '%v %s');
        },
    }
}
