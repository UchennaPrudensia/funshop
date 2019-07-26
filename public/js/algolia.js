(function(){
var client = algoliasearch('0YVVNOGN5L', '643726f5a85d607ab6f8adb2c028f7fd');
var index = client.initIndex('products');
//initialize autocomplete on search input (ID selector must match)
autocomplete('#aa-search-input',
{ hint: true }, {
    source: autocomplete.sources.hits(index, {hitsPerPage: 8}),
    //value to be displayed in input control after user's suggestion selection
    displayKey: 'name',
    //hash of templates used when rendering dataset
    templates: {
        //'suggestion' templating function used to render a single suggestion
        suggestion: function(suggestion) {
          return '<span>' +
            suggestion._highlightResult.name.value + '</span><span>' +
            suggestion.price + '</span>';
        }
    }
});

})();
