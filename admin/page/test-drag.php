<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category & Subcategory Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .category {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .category.dragging {
            transform: scale(1.03);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .category h5 {
            font-size: 16px;
            display: flex;
            align-items: center;
        }

        .category h5 span {
            width: 12px;
            height: 12px;
            display: inline-block;
            margin-right: 10px;
            border-radius: 50%;
        }

        .drag-icon {
            font-size: 16px;
            margin-right: 10px;
            cursor: grab;
        }

        .subcategory-container {
            margin-top: 20px;
            padding-left: 20px;
            min-height: 20px;
            border: none; /* ปิดเส้นประ */
        }

        .subcategory-item {
            background-color: #f1f3f5;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 14px;
            cursor: grab;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            align-items: center;
        }

        .subcategory-item.dragging {
            transform: scale(1.03);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .subcategory-item .drag-icon {
            font-size: 16px;
            margin-right: 10px;
            cursor: grab;
        }

        .add-subcategory-btn {
            font-size: 14px;
            color: #0d6efd;
            cursor: pointer;
            margin-top: 10px;
        }

        .add-subcategory-btn:hover {
            text-decoration: underline;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="header">
            <h2>Categories</h2>
            <div class="header-actions">
                <button id="add-category-btn" class="btn btn-primary">+ Add</button>
                <button class="btn btn-outline-secondary">Actions</button>
            </div>
        </div>
        <p>4 categories</p>

        <div id="category-container">
            <!-- Category 1 -->
            <div class="category" data-id="1">
                <h5>
                    <span class="drag-icon">☰</span>
                    <span style="background-color: #0d6efd;"></span> Content
                </h5>
                <div class="subcategory-container">
                    <div class="subcategory-item" data-id="1-1">
                        <span class="drag-icon">☰</span> Guides & articles
                    </div>
                    <div class="subcategory-item" data-id="1-2">
                        <span class="drag-icon">☰</span> Tutorials & advices
                    </div>
                </div>
                <div class="add-subcategory-btn">+ Add subcategory</div>
            </div>

            <!-- Category 2 -->
            <div class="category" data-id="2">
                <h5>
                    <span class="drag-icon">☰</span>
                    <span style="background-color: #e83e8c;"></span> Internal Communication
                </h5>
                <div class="subcategory-container">
                    <div class="subcategory-item" data-id="2-1">
                        <span class="drag-icon">☰</span> Steeple news & events
                    </div>
                    <div class="subcategory-item" data-id="2-2">
                        <span class="drag-icon">☰</span> Launch
                    </div>
                </div>
                <div class="add-subcategory-btn">+ Add subcategory</div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.3/dragula.min.js"></script>
    <script>
        const categoryContainer = document.getElementById('category-container');
        const addCategoryBtn = document.getElementById('add-category-btn');

        // Initialize Dragula for drag-and-drop functionality
        const drake = dragula({
            containers: Array.from(document.querySelectorAll('.subcategory-container, #category-container')),
            moves: (el, container, handle) => {
                // Allow dragging only if the handle is a drag icon
                return handle.classList.contains('drag-icon') || el.classList.contains('subcategory-item');
            },
            accepts: (el, target) => {
                // Prevent categories from being dropped into subcategory containers
                if (el.classList.contains('category') && target.classList.contains('subcategory-container')) {
                    return false;
                }
                return true;
            }
        });

        // Add dragging class for better animation
        drake.on('drag', (el) => {
            el.classList.add('dragging');
        });

        drake.on('dragend', (el) => {
            el.classList.remove('dragging');
        });

        // Add new category dynamically
        addCategoryBtn.addEventListener('click', () => {
            const newCategoryId = Date.now();
            const newCategory = document.createElement('div');
            newCategory.classList.add('category');
            newCategory.setAttribute('data-id', newCategoryId);

            newCategory.innerHTML = `
                <h5>
                    <span class="drag-icon">☰</span>
                    <span style="background-color: #ffc107;"></span> New Category
                </h5>
                <div class="subcategory-container"></div>
                <div class="add-subcategory-btn">+ Add subcategory</div>
            `;

            // Add the new category to the container
            categoryContainer.appendChild(newCategory);

            // Add click event to the "Add subcategory" button
            newCategory.querySelector('.add-subcategory-btn').addEventListener('click', () => {
                addSubcategory(newCategory.querySelector('.subcategory-container'));
            });
        });

        // Add subcategory dynamically
        function addSubcategory(container) {
            const newSubcategoryId = Date.now();
            const newSubcategory = document.createElement('div');
            newSubcategory.classList.add('subcategory-item');
            newSubcategory.setAttribute('data-id', newSubcategoryId);
            newSubcategory.innerHTML = `
                <span class="drag-icon">☰</span> New Subcategory
            `;

            container.appendChild(newSubcategory);
        }

        // Add "Add subcategory" button functionality to existing categories
        document.querySelectorAll('.add-subcategory-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const subcategoryContainer = btn.previousElementSibling;
                addSubcategory(subcategoryContainer);
            });
        });
    </script>
</body>

</html>
