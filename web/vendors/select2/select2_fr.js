function select2_langue() {
    return{inputTooLong: function (e) {
            var t = e.input.length - e.maximum, n = "Supprimez " + t + " caractère";
            return t !== 1 && (n += "s"), n;
        }, inputTooShort: function (e) {
            var t = e.minimum - e.input.length, n = "Saisissez " + t + " caractère";
            return t !== 1 && (n += "s"), n;
        }, loadingMore: function () {
            return"Chargement de résultats supplémentaires...";
        }, maximumSelected: function (e) {
            var t = "Vous pouvez seulement sélectionner " + e.maximum + " élément";
            return e.maximum !== 1 && (t += "s"), t;
        }, noResults: function () {
            return"Aucun résultat trouvé";
        }, searching: function () {
            return"Recherche en cours...";
        }};
}