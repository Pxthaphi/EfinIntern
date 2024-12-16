document.addEventListener('DOMContentLoaded', function () {
    let container = document.getElementById('container');
    let spinner = document.getElementById('spinner');
    let page = 1;
    let itemsPerLoad = 6; // Number of items to load each time
    let currentItems = 0; // Start loading from the beginning of the data array

    const data = [
        [
            { img: './assets/images/port_img/port1.webp', title: 'Design Web Portfolio 1', category: 'Web Design', link: '#' },
            { img: './assets/images/port_img/img1.webp', title: 'Design Web Portfolio 2', category: 'Web Design', link: '#' },
            { img: './assets/images/port_img/port2.webp', title: 'Design Web Portfolio 3', category: 'Web Design', link: '#' },
            { img: './assets/images/port_img/port1.webp', title: 'Design Web Portfolio 4', category: 'Web Design', link: '#' },
            { img: './assets/images/port_img/port2.webp', title: 'Design Web Portfolio 5', category: 'Web Design', link: '#' },
            { img: './assets/images/port_img/port3.webp', title: 'Design Web Portfolio 6', category: 'Web Design', link: '#' }
        ],
        [
            { img: './assets/images/port_img/port1.webp', title: 'Design Web Portfolio 7', category: 'Web Design', link: '#' },
            { img: './assets/images/port_img/port2.webp', title: 'Design Web Portfolio 8', category: 'Web Design', link: '#' },
            { img: './assets/images/port_img/img1.webp', title: 'Design Web Portfolio 9', category: 'Web Design', link: '#' },
            { img: './assets/images/port_img/port2.webp', title: 'Design Web Portfolio 10', category: 'Web Design', link: '#' },
            { img: './assets/images/port_img/img1.webp', title: 'Design Web Portfolio 11', category: 'Web Design', link: '#' },
            { img: './assets/images/port_img/port3.webp', title: 'Design Web Portfolio 12', category: 'Web Design', link: '#' }
        ],
        [
            { img: './assets/images/port_img/img1.webp', title: 'Design Web Portfolio 13', category: 'Web Design', link: '#' },
            { img: './assets/images/port_img/img1.webp', title: 'Design Web Portfolio 14', category: 'Web Design', link: '#' },
            { img: './assets/images/port_img/img1.webp', title: 'Design Web Portfolio 15', category: 'Web Design', link: '#' },
            { img: './assets/images/port_img/port1.webp', title: 'Design Web Portfolio 16', category: 'Web Design', link: '#' },
            { img: './assets/images/port_img/port2.webp', title: 'Design Web Portfolio 17', category: 'Web Design', link: '#' },
            { img: './assets/images/port_img/port3.webp', title: 'Design Web Portfolio 18', category: 'Web Design', link: '#' }
        ]
    ];

    const screenHeight = window.innerHeight;
    const screenWidth = window.innerWidth;
    if (screenWidth >= 1921) {
        loadMoreContent(); // Load more content immediately for screens 2300px or wider
    } 

    window.addEventListener('scroll', function () {
        const screenHeight = window.innerHeight;
        const screenWidth = window.innerWidth;
        let threshold = Math.max(500, screenHeight * 0.25);

        console.log('Window Height:', screenHeight);
        console.log('Scroll Y:', window.scrollY);
        console.log('Document Height:', document.body.offsetHeight);
        console.log('Threshold:', threshold);

        if (screenWidth >= 2300) {
            loadMoreContent(); // Load more content immediately for screens 2300px or wider
        } else if (screenHeight + window.scrollY >= document.body.offsetHeight - threshold) {
            loadMoreContent();
        }
    });

    function loadMoreContent() {
        if (spinner.style.display === 'block') {
            return;
        }
        if (currentItems >= data.length * itemsPerLoad) {
            return;
        }

        spinner.style.display = 'block';

        setTimeout(() => {
            let newContent = '';
            let row = document.createElement('div');
            row.className = 'row';

            for (let i = 0; i < itemsPerLoad && currentItems < data.length * itemsPerLoad; i++) {
                let dataIndex = Math.floor(currentItems / itemsPerLoad);
                let itemIndex = currentItems % itemsPerLoad;

                // Check if current item index is within range of data array
                if (dataIndex < data.length && itemIndex < data[dataIndex].length) {
                    let item = data[dataIndex][itemIndex];
                    newContent += `
                        <div class="col-xxl-6 col-xl-6 col-lg-4 col-md-6 col-12 cardport">
                            <div class="card-port">
                                <a href="${item.link}">
                                    <img src="${item.img}" alt="" class="img-port">
                                </a>
                                <a href="${item.link}">
                                    <h6>${item.title}</h6>
                                </a>
                                <div class="describe-card d-flex justify-content-between">
                                    <p>${item.category}</p>
                                </div>
                            </div>
                        </div>
                    `;
                }
                currentItems++;
            }

            // Remove any existing spinner before adding new content
            if (container.lastElementChild === spinner) {
                container.removeChild(spinner);
            }

            // Add new content without adding extra empty rows
            row.innerHTML = newContent.trim();
            container.appendChild(row);

            // Re-append spinner to the end
            container.appendChild(spinner);

            spinner.style.display = 'none';
            page++;
        }, 2000); // Simulate a network delay
    }
});
