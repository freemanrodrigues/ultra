/**
 * Generalized jQuery Autocomplete Plugin
 * Usage: $('#input-id').autocomplete(options)
 */

(function($) {
    'use strict';

    // Default options
    const defaults = {
        url: '',                           // AJAX endpoint URL
        method: 'GET',                     // HTTP method
        minLength: 1,                      // Minimum characters to trigger search
        debounceTime: 300,                 // Debounce delay in milliseconds
        maxResults: 20,                    // Maximum number of results to display
        queryParam: 'query',               // Query parameter name
        additionalParams: {},              // Additional parameters to send with request
        displayField: 'name',              // Field to display in suggestions
        valueField: 'id',                  // Field to use as value
        searchFields: ['name'],            // Fields to search in (for highlighting)
        
        // Selectors
        suggestionsContainer: null,        // Container for suggestions (auto-generated if null)
        loadingIndicator: null,            // Loading indicator element
        selectedDisplay: null,             // Element to show selected item info
        hiddenField: null,                 // Hidden field to store selected ID
        
        // Styling
        containerClass: 'autocomplete-suggestions',
        itemClass: 'suggestion-item',
        loadingClass: 'autocomplete-loading',
        noResultsClass: 'no-results',
        highlightClass: 'highlight',
        
        // Templates
        itemTemplate: function(item, displayField, query) {
            const display = item[displayField] || '';
            const highlighted = this.highlightMatch(display, query);
            return `<div class="${this.itemClass}" data-id="${item[this.valueField]}" data-name="${display}">${highlighted}</div>`;
        },
        
        noResultsTemplate: '<div class="p-3 text-muted no-results">No results found.</div>',
        errorTemplate: '<div class="p-3 text-danger">Error fetching suggestions.</div>',
        
        // Callbacks
        onSelect: function(item, element) {},
        onClear: function(element) {},
        beforeRequest: function(query, params) { return params; },
        formatResponse: function(response) { return response; },
        
        // Custom request function (override for custom AJAX behavior)
        customRequest: null
    };

    function AutoComplete(element, options) {
        this.element = $(element);
        this.options = $.extend(true, {}, defaults, options);
        this.timeout = null;
        this.currentQuery = '';
        this.isVisible = false;
        
        this.init();
    }

    AutoComplete.prototype = {
        init: function() {
            this.setupContainers();
            this.bindEvents();
        },

        setupContainers: function() {
            // Create suggestions container if not provided
            if (!this.options.suggestionsContainer) {
                this.suggestionsContainer = $(`<div class="${this.options.containerClass} d-none"></div>`);
                this.element.after(this.suggestionsContainer);
            } else {
                this.suggestionsContainer = $(this.options.suggestionsContainer);
            }

            // Setup loading indicator
            if (this.options.loadingIndicator) {
                this.loadingIndicator = $(this.options.loadingIndicator);
            }

            // Setup selected display
            if (this.options.selectedDisplay) {
                this.selectedDisplay = $(this.options.selectedDisplay);
            }

            // Setup hidden field
            if (this.options.hiddenField) {
                this.hiddenField = $(this.options.hiddenField);
            }
        },

        bindEvents: function() {
            const self = this;

            // Input events
            this.element.on('keyup.autocomplete', function(e) {
                // Ignore arrow keys, enter, escape
                if ([38, 40, 13, 27].includes(e.keyCode)) return;
                self.handleInput();
            });

            this.element.on('keydown.autocomplete', function(e) {
                self.handleKeydown(e);
            });

            this.element.on('focus.autocomplete', function() {
                if (self.currentQuery && self.suggestionsContainer.find(`.${self.options.itemClass}`).length) {
                    self.showSuggestions();
                }
            });

            // Suggestion item click
            this.suggestionsContainer.on('click', `.${this.options.itemClass}`, function() {
                self.selectItem($(this));
            });

            // Click outside to hide
            $(document).on('click.autocomplete', function(e) {
                if (!$(e.target).closest(self.element).length && 
                    !$(e.target).closest(self.suggestionsContainer).length) {
                    self.hideSuggestions();
                }
            });
        },

        handleInput: function() {
            const query = this.element.val().trim();
            this.currentQuery = query;

            clearTimeout(this.timeout);

            if (query.length < this.options.minLength) {
                this.hideSuggestions();
                this.options.onClear(this.element);
                return;
            }

            this.showLoading();

            this.timeout = setTimeout(() => {
                this.performSearch(query);
            }, this.options.debounceTime);
        },

        handleKeydown: function(e) {
            const $items = this.suggestionsContainer.find(`.${this.options.itemClass}`);
            const $active = $items.filter('.active');

            switch (e.keyCode) {
                case 38: // Up arrow
                    e.preventDefault();
                    this.navigateUp($items, $active);
                    break;
                case 40: // Down arrow
                    e.preventDefault();
                    this.navigateDown($items, $active);
                    break;
                case 13: // Enter
                    e.preventDefault();
                    if ($active.length) {
                        this.selectItem($active);
                    }
                    break;
                case 27: // Escape
                    this.hideSuggestions();
                    this.element.blur();
                    break;
            }
        },

        navigateUp: function($items, $active) {
            $items.removeClass('active');
            if ($active.length && $active.prev().length) {
                $active.prev().addClass('active');
            } else {
                $items.last().addClass('active');
            }
        },

        navigateDown: function($items, $active) {
            $items.removeClass('active');
            if ($active.length && $active.next().length) {
                $active.next().addClass('active');
            } else {
                $items.first().addClass('active');
            }
        },

        performSearch: function(query) {
            if (this.options.customRequest) {
                this.options.customRequest.call(this, query);
                return;
            }

            const params = $.extend({}, this.options.additionalParams);
            params[this.options.queryParam] = query;

            // Allow modification of parameters before request
            const finalParams = this.options.beforeRequest(query, params);

            $.ajax({
                url: this.options.url,
                method: this.options.method,
                data: finalParams,
                dataType: 'json',
                success: (response) => {
                    this.handleSuccess(response, query);
                },
                error: (xhr, status, error) => {
                    this.handleError(xhr, status, error);
                }
            });
        },

        handleSuccess: function(response, query) {
            this.hideLoading();
            this.suggestionsContainer.empty();

            // Allow response formatting
            const formattedResponse = this.options.formatResponse(response);

            if (formattedResponse && formattedResponse.length > 0) {
                const limitedResults = formattedResponse.slice(0, this.options.maxResults);
                
                limitedResults.forEach(item => {
                    const html = this.options.itemTemplate.call(this.options, item, this.options.displayField, query);
                    this.suggestionsContainer.append(html);
                });

                this.showSuggestions();
            } else {
                this.suggestionsContainer.append(this.options.noResultsTemplate);
                this.showSuggestions();
            }
        },

        handleError: function(xhr, status, error) {
            this.hideLoading();
            console.error('Autocomplete AJAX Error:', status, error);
            this.suggestionsContainer.empty().append(this.options.errorTemplate);
            this.showSuggestions();
        },

        selectItem: function($item) {
            const selectedName = $item.data('name');
            const selectedId = $item.data('id');
            const itemData = {
                id: selectedId,
                name: selectedName,
                element: $item
            };

            this.element.val(selectedName);

            if (this.hiddenField) {
                this.hiddenField.val(selectedId);
            }

            if (this.selectedDisplay) {
                this.selectedDisplay.text(`${selectedName} (ID: ${selectedId})`);
            }

            this.hideSuggestions();
            this.options.onSelect(itemData, this.element);
        },

        highlightMatch: function(text, query) {
            if (!query) return text;
            
            const regex = new RegExp(`(${this.escapeRegExp(query)})`, 'gi');
            return text.replace(regex, `<span class="${this.options.highlightClass}">$1</span>`);
        },

        escapeRegExp: function(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        },

        showLoading: function() {
            if (this.loadingIndicator) {
                this.loadingIndicator.show();
            }
        },

        hideLoading: function() {
            if (this.loadingIndicator) {
                this.loadingIndicator.hide();
            }
        },

        showSuggestions: function() {
            this.suggestionsContainer.removeClass('d-none');
            this.isVisible = true;
        },

        hideSuggestions: function() {
            this.suggestionsContainer.addClass('d-none');
            this.isVisible = false;
        },

        // Public methods
        clear: function() {
            this.element.val('');
            if (this.hiddenField) {
                this.hiddenField.val('');
            }
            if (this.selectedDisplay) {
                this.selectedDisplay.empty();
            }
            this.hideSuggestions();
            this.options.onClear(this.element);
        },

        setValue: function(id, name) {
            this.element.val(name);
            if (this.hiddenField) {
                this.hiddenField.val(id);
            }
            if (this.selectedDisplay) {
                this.selectedDisplay.text(`${name} (ID: ${id})`);
            }
        },

        destroy: function() {
            clearTimeout(this.timeout);
            this.element.off('.autocomplete');
            $(document).off('.autocomplete');
            this.suggestionsContainer.remove();
        }
    };

    // jQuery plugin
    $.fn.autocomplete = function(options) {
        return this.each(function() {
            if (!$.data(this, 'autocomplete')) {
                $.data(this, 'autocomplete', new AutoComplete(this, options));
            }
        });
    };

    // Make methods accessible
    $.fn.autocomplete.methods = {
        clear: function() {
            return this.each(function() {
                const instance = $.data(this, 'autocomplete');
                if (instance) instance.clear();
            });
        },
        setValue: function(id, name) {
            return this.each(function() {
                const instance = $.data(this, 'autocomplete');
                if (instance) instance.setValue(id, name);
            });
        },
        destroy: function() {
            return this.each(function() {
                const instance = $.data(this, 'autocomplete');
                if (instance) {
                    instance.destroy();
                    $.removeData(this, 'autocomplete');
                }
            });
        }
    };

})(jQuery);

// Usage Examples:

// Basic usage
$('#customer_name').autocomplete({
    url: '/ajax/autosuggest-customer',
    displayField: 'company_name',
    valueField: 'id'
});

// Advanced usage with custom options
$('#product_search').autocomplete({
    url: '/ajax/search-products',
    method: 'POST',
    minLength: 2,
    debounceTime: 500,
    maxResults: 15,
    displayField: 'product_name',
    valueField: 'product_id',
    queryParam: 'search',
    additionalParams: {
        category: 'electronics',
        status: 'active'
    },
    hiddenField: '#product_id',
    selectedDisplay: '#selected_product',
    
    // Custom item template
    itemTemplate: function(item, displayField, query) {
        const highlighted = this.highlightMatch(item[displayField], query);
        return `
            <div class="${this.itemClass}" data-id="${item[this.valueField]}" data-name="${item[displayField]}">
                <div class="fw-bold">${highlighted}</div>
                <small class="text-muted">${item.category} - $${item.price}</small>
            </div>
        `;
    },
    
    // Callbacks
    onSelect: function(item, element) {
        console.log('Selected:', item);
        $('#product_price').val(item.element.find('small').text().match(/\$(\d+)/)[1]);
    },
    
    beforeRequest: function(query, params) {
        // Add authentication token
        params._token = $('meta[name="csrf-token"]').attr('content');
        return params;
    },
    
    formatResponse: function(response) {
        // Handle different response formats
        return response.data || response;
    }
});

// Multiple instances
$('#search').autocomplete({
    url: '/ajax/autosuggest-customer',
    displayField: 'search',
    valueField: 'user_id',
   
});

$('#site_name').autocomplete({
    url: '/ajax/autosuggest-sitename',
    displayField: 'site_name',
    valueField: 'site_name_id',
    
});

$('#vendor_search').autocomplete({
    url: '/ajax/search-vendors',
    displayField: 'vendor_name',
    valueField: 'vendor_id',
    additionalParams: { status: 'active' }
});

// Method calls
$('#search').autocomplete('clear');
$('#search').autocomplete('setValue', 123, 'John Doe');
$('#search').autocomplete('destroy');