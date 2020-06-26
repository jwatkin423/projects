$(document).ready(function () {
    var $searchUrl = $('form#search-main-all').attr('data-url');
    var $url = $searchUrl + '/products';

    var apiCategoriesEngine = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: $url +'/%QUERY',
            wildcard: '%QUERY'
        }
    });

    apiCategoriesEngine.clearPrefetchCache();
    apiCategoriesEngine.initialize();

    $('#search-all').typeahead(null, {
            hint: true,
            highlight: true,
            minLength: 1,
            display: 'prod_name',
            source: apiCategoriesEngine,
            templates: {
                empty: ['<div class="empty-message">Unable to find any Categories</div>'],
                suggestion: function(data) {
                    return '<a class="list-group-item tt-highlight">' + data.prod_name + '</a>';
                }
            }
        }
    ).on('typeahead:selected', function (obj, datum) {
            // Set the cat_Id
            $("#catId").val(datum['id']);
        });

    // Add class to override typeahaed CSS
    $('input.tt-input').addClass('tt-input-override');
});
