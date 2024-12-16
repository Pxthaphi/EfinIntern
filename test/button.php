<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SweetAlert with Custom Buttons</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/ldrs/dist/auto/ring.js"></script>
</head>

<body class="flex items-center justify-center h-screen bg-gradient-to-r from-green-400 to-blue-500">

    <button
        id="sweetalert-button"
        class="px-6 py-3 text-white bg-gradient-to-r from-red-500 to-yellow-500 rounded-lg shadow-lg hover:from-red-600 hover:to-yellow-600 focus:outline-none focus:ring-4 focus:ring-yellow-300 transition-transform transform hover:scale-105">
        Show Alert
    </button>

    <script>
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg",
                cancelButton: "bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg"
            },
            buttonsStyling: false
        });

        document.getElementById('sweetalert-button').addEventListener('click', () => {
            swalWithBootstrapButtons.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        html: `
                            <div class="flex flex-col items-center">
                                <l-ring
                                    size="60"
                                    stroke="5"
                                    bg-opacity="0"
                                    speed="2"
                                    color="green">
                                </l-ring>
                                <h2 class="text-3xl font-semibold mt-8">กำลังบันทึกข้อมูล</h2>
                                <p class="mt-6">กรุณารอสักครู่...</p>
                            </div>
                        `,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        timer: 3000, // ตั้งเวลา 3 วินาที
                        willClose: () => {
                            // ตัวอย่าง: ตรวจสอบเงื่อนไข
                            const isSuccess = true; // กำหนดสถานะตามผลลัพธ์จริง

                            if (isSuccess) {
                                swalWithBootstrapButtons.fire({
                                    title: "สำเร็จ!",
                                    text: "ข้อมูลของคุณถูกบันทึกเรียบร้อยแล้ว",
                                    icon: "success",
                                    confirmButtonText: "ตกลง"
                                });
                            } else {
                                swalWithBootstrapButtons.fire({
                                    title: "ล้มเหลว!",
                                    text: "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง",
                                    icon: "error",
                                    confirmButtonText: "ตกลง"
                                });
                            }
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelled",
                        text: "Your imaginary file is safe :)",
                        icon: "error"
                    });
                }
            });
        });
    </script>
</body>

</html>