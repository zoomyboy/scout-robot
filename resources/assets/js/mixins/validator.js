export default {
    methods: {
        validateLength: function() {
            return function(v) {
                if (typeof v == "undefined") {return 'Dieses Feld ist erforderlich';}
                return (v.length > 0) || 'Dieses Feld ist erforderlich.';
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
        }
    }
}
