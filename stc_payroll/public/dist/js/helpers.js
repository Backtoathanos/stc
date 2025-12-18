/**
 * STC Payroll - JavaScript Helpers
 * Reusable functions for the application
 */

(function($) {
    'use strict';

    /**
     * Auto-save function for individual fields
     * Saves field value on blur/change and shows success message
     * 
     * @param {string} fieldName - Name of the field
     * @param {mixed} fieldValue - Value to save
     * @param {jQuery} fieldElement - jQuery element of the field
     * @param {string} updateUrl - URL to update the record
     * @param {function} onSuccess - Optional callback on success
     * @param {function} onError - Optional callback on error
     */
    window.saveField = function(fieldName, fieldValue, fieldElement, updateUrl, onSuccess, onError) {
        // Get the record ID from the form or specific input
        var id = fieldElement.closest('form').find('input[name="id"], input[id*="EmployeeId"]').first().val();
        if (!id) {
            // Try to get from global variable or data attribute
            id = $('#editEmployeeId').val() || fieldElement.closest('form').data('record-id');
        }
        if (!id) {
            console.error('No ID found for saving field');
            return;
        }
        
        // Convert to integer for ID fields
        if (fieldName === 'site_id' || fieldName === 'department_id' || fieldName === 'designation_id' || fieldName === 'gang_id') {
            fieldValue = parseInt(fieldValue);
            if (isNaN(fieldValue)) {
                console.error('Invalid ID value for ' + fieldName);
                return;
            }
        }
        
        // Build update URL
        var finalUpdateUrl = updateUrl;
        if (!finalUpdateUrl || !finalUpdateUrl.includes('/' + id)) {
            var baseUrl = window.baseUrl || '/stc/stc_payroll/public';
            if (updateUrl && updateUrl.includes('/master/employees')) {
                finalUpdateUrl = baseUrl + '/master/employees/' + id;
            } else if (updateUrl) {
                finalUpdateUrl = updateUrl.replace('{id}', id);
            } else {
                finalUpdateUrl = baseUrl + '/master/employees/' + id;
            }
        }
        
        var data = {};
        data[fieldName] = fieldValue;
        data._method = 'PUT';
        
        $.ajax({
            url: finalUpdateUrl,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Remove any existing success message
                    fieldElement.next('.field-success-message').remove();
                    fieldElement.closest('.form-group').find('.field-success-message').remove();
                    fieldElement.closest('.searchable-dropdown').next('.field-success-message').remove();
                    
                    // Add success message
                    var successMsg = $('<p class="field-success-message text-success mb-0" style="font-size: 12px; margin-top: 5px;"><i class="fas fa-check-circle"></i> Saved</p>');
                    
                    // If it's a searchable dropdown input, append after the container
                    if (fieldElement.hasClass('searchable-input')) {
                        fieldElement.closest('.searchable-dropdown').after(successMsg);
                    } else {
                        // For regular fields, append to form-group
                        fieldElement.closest('.form-group').append(successMsg);
                    }
                    
                    // Remove message after 1.5 seconds
                    setTimeout(function() {
                        successMsg.fadeOut(300, function() {
                            $(this).remove();
                        });
                    }, 1500);
                    
                    // Call success callback if provided
                    if (typeof onSuccess === 'function') {
                        onSuccess(response);
                    }
                } else {
                    // Call error callback if provided
                    if (typeof onError === 'function') {
                        onError(response);
                    }
                }
            },
            error: function(xhr) {
                // Show error message
                fieldElement.next('.field-success-message').remove();
                fieldElement.closest('.form-group').find('.field-success-message').remove();
                var errorMsg = $('<p class="field-success-message text-danger mb-0" style="font-size: 12px; margin-top: 5px;"><i class="fas fa-exclamation-circle"></i> Error saving</p>');
                
                if (fieldElement.hasClass('searchable-input')) {
                    fieldElement.closest('.searchable-dropdown').after(errorMsg);
                } else {
                    fieldElement.closest('.form-group').append(errorMsg);
                }
                
                setTimeout(function() {
                    errorMsg.fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 2000);
                
                // Call error callback if provided
                if (typeof onError === 'function') {
                    onError(xhr);
                }
            }
        });
    };

    /**
     * Initialize searchable dropdown
     * Converts a select element to a searchable dropdown with "Add new" functionality
     * 
     * @param {HTMLElement|jQuery} selectElement - The select element to convert
     * @param {string} createUrl - URL to create new items (optional, for "Add new" feature)
     */
    window.initSearchableDropdown = function(selectElement, createUrl) {
        var $select = $(selectElement);
        var $wrapper = $select.parent();
        
        // Skip if already initialized
        if ($select.siblings('.searchable-dropdown').length > 0) {
            return;
        }
        
        // Create searchable dropdown container
        var $container = $('<div class="searchable-dropdown" style="position: relative;"></div>');
        var $input = $('<input type="text" class="form-control searchable-input" placeholder="Type to search' + (createUrl ? ' or add new...' : '...') + '" autocomplete="off">');
        var $dropdown = $('<ul class="searchable-results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-top: none; max-height: 200px; overflow-y: auto; z-index: 1000; display: none; list-style: none; padding: 0; margin: 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></ul>');
        
        $select.hide();
        $container.append($input);
        $container.append($dropdown);
        $wrapper.append($container);
        
        // Populate options
        var options = [];
        $select.find('option').each(function() {
            if ($(this).val()) {
                options.push({
                    value: $(this).val(),
                    text: $(this).text()
                });
            }
        });
        
        // Set initial value
        if ($select.val()) {
            var selectedOption = options.find(opt => opt.value == $select.val());
            if (selectedOption) {
                $input.val(selectedOption.text);
            }
        }
        
        // Filter and show results
        function filterResults(searchTerm, showAll) {
            $dropdown.empty();
            var filtered = options.filter(function(opt) {
                if (showAll || !searchTerm) {
                    return true; // Show all options
                }
                return opt.text.toLowerCase().includes(searchTerm.toLowerCase());
            });
            
            if (filtered.length > 0) {
                filtered.forEach(function(opt) {
                    var $li = $('<li style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #eee;" data-value="' + opt.value + '">' + opt.text + '</li>');
                    $li.on('click', function() {
                        $select.val($(this).data('value')).trigger('change');
                        $input.val($(this).text());
                        $dropdown.hide();
                    });
                    $li.on('mouseenter', function() {
                        $(this).css('background-color', '#f0f0f0');
                    });
                    $li.on('mouseleave', function() {
                        $(this).css('background-color', 'white');
                    });
                    $dropdown.append($li);
                });
            } else if (searchTerm.length > 0 && createUrl) {
                // Show "Add new" option
                var $addNew = $('<li style="padding: 8px 12px; cursor: pointer; background-color: #e7f3ff; color: #0066cc; font-weight: bold;" class="add-new-option">+ Add new: "' + searchTerm + '"</li>');
                $addNew.on('click', function() {
                    var newName = searchTerm.trim();
                    if (newName) {
                        $.ajax({
                            url: createUrl,
                            type: 'POST',
                            data: { name: newName },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success && response.data) {
                                    // Extract ID and name from response
                                    var newId = response.data.id;
                                    var newName = response.data.name || searchTerm.trim();
                                    
                                    // Validate that we have both ID and name
                                    if (!newId || !newName) {
                                        console.error('Invalid response data:', response);
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Invalid response from server'
                                        });
                                        return;
                                    }
                                    
                                    // Add new option to select
                                    var $newOption = $('<option value="' + newId + '">' + newName + '</option>');
                                    $select.append($newOption);
                                    
                                    // Add to options array
                                    options.push({
                                        value: newId,
                                        text: newName
                                    });
                                    
                                    // Select the new option
                                    $select.val(newId).trigger('change');
                                    $input.val(newName);
                                    $dropdown.hide();
                                    
                                    // Trigger auto-save if in edit mode
                                    var fieldName = $select.attr('name');
                                    if (fieldName) {
                                        var id = $('#editEmployeeId').val();
                                        if (id) {
                                            var baseUrl = window.baseUrl || '/stc/stc_payroll/public';
                                            var updateUrl = baseUrl + '/master/employees/' + id;
                                            window.saveField(fieldName, newId, $input, updateUrl);
                                        }
                                    }
                                    
                                    // Show success message
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: 'New item added successfully',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                } else {
                                    console.error('Invalid response:', response);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Failed to add new item: Invalid response'
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to add new item'
                                });
                            }
                        });
                    }
                });
                $addNew.on('mouseenter', function() {
                    $(this).css('background-color', '#cce7ff');
                });
                $addNew.on('mouseleave', function() {
                    $(this).css('background-color', '#e7f3ff');
                });
                $dropdown.append($addNew);
            }
            
            // Show dropdown if there are filtered results or if showing "Add new" or if showing all
            if (filtered.length > 0 || (searchTerm.length > 0 && createUrl) || showAll) {
                $dropdown.show();
            } else {
                $dropdown.hide();
            }
        }
        
        // Input events
        $input.on('input', function() {
            var searchTerm = $(this).val();
            filterResults(searchTerm, false);
        });
        
        $input.on('focus click', function() {
            // Always show all options when clicking/focusing on the input
            filterResults('', true);
        });
        
        // Hide dropdown when clicking outside (but not when clicking the input)
        $(document).on('click', function(e) {
            if (!$(e.target).closest($container).length && !$(e.target).is($input)) {
                $dropdown.hide();
            }
        });
        
        // Update input when select changes programmatically
        $select.on('change', function() {
            var selectedValue = $(this).val();
            var selectedOption = options.find(opt => opt.value == selectedValue);
            if (selectedOption) {
                $input.val(selectedOption.text);
            } else {
                $input.val('');
            }
        });
        
        // Expose a method to update the dropdown value programmatically
        $select.data('searchable-dropdown-update', function(value) {
            $select.val(value).trigger('change');
        });
    };

    /**
     * Setup auto-save for form fields
     * Automatically saves fields on blur/change
     * 
     * @param {string} formSelector - Selector for the form (e.g., '#editEmployeeForm')
     * @param {string} updateUrl - Base URL for updates (optional)
     */
    window.setupAutoSave = function(formSelector, updateUrl) {
        var $form = $(formSelector);
        if ($form.length === 0) return;
        
        // Auto-save on blur for text inputs
        $form.find('input[type="text"], input[type="email"], input[type="date"], textarea').on('blur', function() {
            // Skip if form is loading data
            if ($form.data('loading')) return;
            
            var fieldName = $(this).attr('name');
            var fieldValue = $(this).val();
            if (fieldName && fieldName !== 'id') {
                window.saveField(fieldName, fieldValue, $(this), updateUrl);
            }
        });
        
        // Auto-save on change for select dropdowns
        $form.find('select').on('change', function() {
            // Skip if form is loading data
            if ($form.data('loading')) return;
            
            var fieldName = $(this).attr('name');
            var fieldValue = $(this).val();
            if (fieldName) {
                // For searchable dropdowns, use the input element
                var $input = $(this).siblings('.searchable-dropdown').find('.searchable-input');
                var fieldElement = $input.length > 0 ? $input : $(this);
                window.saveField(fieldName, fieldValue, fieldElement, updateUrl);
            }
        });
        
        // Auto-save on change for checkboxes
        $form.find('input[type="checkbox"]').on('change', function() {
            // Skip if form is loading data
            if ($form.data('loading')) return;
            
            var fieldName = $(this).attr('name');
            var fieldValue = $(this).is(':checked') ? '1' : '0';
            if (fieldName) {
                window.saveField(fieldName, fieldValue, $(this), updateUrl);
            }
        });
    };

    /**
     * Format date for display
     * 
     * @param {string} date - Date string
     * @returns {string} Formatted date or 'N/A'
     */
    window.formatDate = function(date) {
        if (!date) return 'N/A';
        if (typeof date === 'string' && date.includes('T')) {
            return date.split('T')[0];
        }
        return date;
    };

    /**
     * Format boolean for display
     * 
     * @param {boolean} value - Boolean value
     * @returns {string} HTML badge
     */
    window.formatBoolean = function(value) {
        return value ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-secondary">No</span>';
    };

    // Set base URL if not already set
    if (typeof window.baseUrl === 'undefined') {
        window.baseUrl = '/stc/stc_payroll/public';
    }

})(jQuery);

