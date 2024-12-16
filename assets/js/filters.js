$(document).ready(function() {
    const tagContainer = document.getElementById('tagContainer');
    var $grid = $('.onovo-portfolio-items').isotope({
        itemSelector: '.onovo-portfolio-col',
        layoutMode: 'fitRows'
    });

    var categoryFilters = [];
    var yearFilter = '';

    function updateIsotope() {
        var filterValues = categoryFilters.join(', ') || '*';
        if (yearFilter) {
            filterValues = filterValues.split(', ').map(filter => filter + '[intern-year="' + yearFilter + '"]').join(', ');
        }
        $grid.isotope({ filter: filterValues });
        console.log($grid.isotope('getFilteredItemElements'));
        updateYearDropdown();
        updateYearDropdown_mobile();
        GetYearwithCategoryFilters();
    }

    function createTag(filterValue, filterTagName) {
        if (Array.from(tagContainer.children).some(tagDiv => tagDiv.getAttribute('data-filter') === filterValue)) {
            return;
        }

        const tagDiv = document.createElement('div');
        tagDiv.className = 'tag-button d-flex align-items-center';
        tagDiv.style.height = '45px';
        tagDiv.setAttribute('data-filter', filterValue);

        const tagText = document.createElement('span');
        tagText.textContent = filterTagName;
        tagDiv.appendChild(tagText);

        const closeIcon = document.createElement('span');
        closeIcon.className = 'material-symbols-outlined';
        closeIcon.textContent = 'close';

        closeIcon.addEventListener('click', () => {
            const checkbox = $(`.filter-box input[data-filter="${filterValue}"]`);
            checkbox.prop('checked', false).change();
            tagDiv.classList.remove('show');
            tagDiv.classList.add('hide');
            setTimeout(() => {
                tagContainer.removeChild(tagDiv);
            }, 300);

            var index = categoryFilters.indexOf(filterValue);
            if (index > -1) {
                categoryFilters.splice(index, 1);
            }
            updateIsotope();
        });

        tagDiv.appendChild(closeIcon);
        tagContainer.appendChild(tagDiv);

        setTimeout(() => {
            tagDiv.classList.add('show');
        }, 10);
    }

    $('.filter-box input[type="checkbox"]').change(function(event) {
        var $this = $(this);
        var filterValue = $this.attr('data-filter');
        var filterTagName = $this.attr('data-tag-name');


        if ($this.is(':checked')) {
            categoryFilters.push(filterValue);
            createTag(filterValue, filterTagName);
        } else {
            var index = categoryFilters.indexOf(filterValue);
            if (index > -1) {
                categoryFilters.splice(index, 1);
            }

            const tagDivs = Array.from(tagContainer.children);
            tagDivs.forEach(tagDiv => {
                if (tagDiv.getAttribute('data-filter') === filterValue) {
                    tagDiv.classList.remove('show');
                    tagDiv.classList.add('hide');
                    setTimeout(() => {
                        tagContainer.removeChild(tagDiv);
                    }, 300);
                }
            });
        }

        updateIsotope();
        updateFilterCount();
        
    });

    function updateYearDropdown() { 
        var uniqueYears = GetYearwithCategoryFilters();

        var sortedYears = Array.from(uniqueYears).sort();
        var dropdownMenu = $('#year-filter-box .dropdown-menu');
        dropdownMenu.empty();

        dropdownMenu.append(`<li><a class="dropdown-item" data-value="">ทั้งหมด</a></li>`);

        sortedYears.forEach(function(year) {
            dropdownMenu.append(`<li><a class="dropdown-item" data-value="${year}">${year}</a></li>`);
        });

        $('.dropdown-menu .dropdown-item').off('click').on('click', function() {
            yearFilter = $(this).attr('data-value') || '';
            var filterText = $(this).text();
            var arrowHtml = '<i class="arrow"></i>';

            if (filterText === 'ทั้งหมด') {
                filterText = 'ปีที่ฝึกงานทั้งหมด';
            } else {
                filterText = 'ปีที่ฝึกงาน : ' + filterText;
            }

            $('#year-filters').html(filterText + arrowHtml);

            updateIsotope();
        });
    }
    function updateYearDropdown_mobile() { 
        var uniqueYears = GetYearwithCategoryFilters();
    
        var sortedYears = Array.from(uniqueYears).sort();
        var dropdownMenu = $('#year-accordion-filter .select-dropdown');
        dropdownMenu.empty();
    
        dropdownMenu.append(`
            <li>
                <input type="radio" id="ทั้งหมด" name="intern-year" value="" />
                <label for="ทั้งหมด">ทั้งหมด</label>
            </li>
        `);
    
        sortedYears.forEach(function(year) {
            dropdownMenu.append(`
                <li>
                    <input type="radio" id="${year}" name="intern-year" value="${year}" />
                    <label for="${year}">${year}</label>
                </li>
            `);
        });
    
        $('#year-accordion-filter input[type="radio"]').off('change').on('change', function() {
            yearFilter = $(this).val() || '';
            var filterText = $(this).next('label').text();
    
            if (filterText === 'ทั้งหมด') {
                filterText = 'ปีที่ฝึกงานทั้งหมด';
            } else {
                filterText = 'ปีที่ฝึกงาน : ' + filterText;
            }
    
            $('.selected-value').text(filterText);
    
            updateIsotope();
        });
    
        // Additional event handling to ensure UI update on selection
        $('#year-accordion-filter .select-dropdown').off('click keyup').on('click keyup', 'li', function(e) {
            if (e.type === "click" && e.clientX !== 0 && e.clientY !== 0) {
                $('.selected-value').text($(this).children('label').text());
                $('#year-accordion-filter').removeClass("active");
            }
            if (e.type === "keyup" && e.key === "Enter") {
                $('.selected-value').text($(this).children('label').text());
                $('#year-accordion-filter').removeClass("active");
            }
        });
    }
    

    // Initial population of the year dropdown based on all items
    updateYearDropdown();
    updateYearDropdown_mobile();
    function GetYearwithCategoryFilters() {
        // If there are no category filters, select all items
        var selector = categoryFilters.length ? categoryFilters.join(', ') : '*';
        var matchingItems = $(selector);
        
        var internYears = new Set();
    
        // Iterate over the matching items to collect unique intern years
        matchingItems.each(function() {
            var year = $(this).attr('intern-year');
            if (year) {
                internYears.add(year);
            }
        });
    
        var uniqueInternYears = Array.from(internYears);
        console.log('Intern years:', uniqueInternYears);
        return uniqueInternYears;
    }
    

    $('.onovo-filter-item').click(function() {
        var $this = $(this);
        var mainFilter = $this.attr('data-filter');
        var mainTagGroup = $this.attr('data-tags');
        var tagFilterElems = $(`.filter-box input[data-tag-group="${mainTagGroup}"]`);

        $('.filter-box input[type="checkbox"]').prop('checked', false);
        categoryFilters = [];
        tagContainer.innerHTML = '';

        if (mainFilter === '*') {
            $('.filter-box input[type="checkbox"]').prop('checked', true).each(function() {
                var filterValue = $(this).attr('data-filter');
                var filterTagName = $(this).attr('data-tag-name');
                categoryFilters.push(filterValue);
                createTag(filterValue, filterTagName);
            });
        } else {
            tagFilterElems.each(function() {
                var filterValue = $(this).attr('data-filter');
                var filterTagName = $(this).attr('data-tag-name');

                $(this).prop('checked', true);
                categoryFilters.push(filterValue);
                createTag(filterValue, filterTagName);
            });
        }

        $('.onovo-filter-item').removeClass('item--active');
        $this.addClass('item--active');

        updateIsotope();
        updateFilterCount();
    });

    const clearButton = document.createElement('button');
    clearButton.className = 'btn btn-clear-filters';
    clearButton.textContent = 'Clear Filters';

    clearButton.addEventListener('click', function() {
        $('.filter-box input[type="checkbox"]').prop('checked', false);
        categoryFilters = [];
        tagContainer.innerHTML = '';

        updateIsotope();
        updateFilterCount();
    });

    document.querySelector('.dropdown-menu').appendChild(clearButton);
    //dropdown mobile size

    $('#year-accordion-filter input[type="radio"]').change(function() {
        // Get the selected radio button's label
        var selectedLabel = $(this).next('label');
        var filterText = selectedLabel.text();

        // Update the displayed text based on selection
        if (filterText === 'ทั้งหมด') {
            filterText = 'ปีที่ฝึกงานทั้งหมด';
        } else {
            filterText = 'ปีที่ฝึกงาน : ' + filterText;
        }

        $('.selected-value').text(filterText);

        // Remove the 'selected' class from all labels first
        $('.custom-select .select-dropdown label').removeClass('selected');

        // Add 'selected' class to the currently selected label
        selectedLabel.addClass('selected');

        // Update the filter (if necessary)
        updateIsotope();
    });
    

    // Dropdown handling for mobile
    const customSelect = document.querySelector(".custom-select");
    const selectBtn = document.querySelector(".select-button");
    const selectedValue = document.querySelector(".selected-value");
    const optionsList = document.querySelectorAll(".select-dropdown li");
    // console.log('optionList : ' , optionsList);

    selectBtn.addEventListener("click", () => {
        customSelect.classList.toggle("active");
        selectBtn.setAttribute(
            "aria-expanded",
            selectBtn.getAttribute("aria-expanded") === "true" ? "false" : "true"
        );
    });

    //selected year dropdown css [Mobile]
    optionsList.forEach((option) => {
        function handler(e) {
            if (e.type === "click" && e.clientX !== 0 && e.clientY !== 0) {
                selectedValue.textContent = this.children[1].textContent;
                customSelect.classList.remove("active");
            }
            if (e.key === "Enter") {
                selectedValue.textContent = this.textContent;
                customSelect.classList.remove("active");
            }
            console.log('removed active!!');
        }

        option.addEventListener("keyup", handler);
        option.addEventListener("click", handler);
    });

    $('#filter_number_box').hide();

    function updateFilterCount() {
        const checkedFiltersCount = $('.filter-box input[type="checkbox"]:checked').length;
        const filterNumberElement = $('#filter_number_box');

        if (checkedFiltersCount === 0 || checkedFiltersCount === null) {
            filterNumberElement.removeClass('show');
            setTimeout(() => {
                filterNumberElement.hide();
            }, 300); 
        } else {
            filterNumberElement.show();
            setTimeout(() => {
                filterNumberElement.addClass('show');
            }, 10); 
        }

        $('#filter_number').text(checkedFiltersCount);
    }

    // Clear button for mobile
    const clearbtn = document.getElementById('clearfilter-mobile');
    clearbtn.addEventListener('click', function() {
        $('.filter-box input[type="checkbox"]').prop('checked', false);
        categoryFilters = [];
        tagContainer.innerHTML = '';

        updateIsotope();
        updateFilterCount();
    });
});
