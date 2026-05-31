<?php

$pageTitle = "home";

session_start();

include "initials.php";

if (isset($_SESSION["email"])) {

    ?>
    <div class="row pt-6 p-md-2 m-0">
        <div class="d-none d-md-block col-lg-3 col-md-4">
        </div>
        <div class="col-12 col-lg-9 col-md-8">

            <div class="card p-2">
                <div class="row m-0">
                    <div class="col-12 col-lg-4 bg-secondary bg-opacity-25 users-list">
                        <h5 class="border-bottom border-warning p-3">المستخدمون</h5>
                        <div class="">

                            <!-- <p>جاري تحميل المستخدمين...</p> -->
                        </div>
                    </div>


                    <div class="position-relative col-12 col-lg-8 bg-warning bg-opacity-25 chat-area" id="chatArea">
                        <div class="border-bottom border-secondary p-3 chat-header">
                            اختر مستخدمًا لبدء المحادثة
                        </div>
                        <div class=" messages-display" id="messagesDisplay">

                        </div>
                        <div class="position-absolute bottom-0 right-0 left-0 message-input-area" id="messageInputArea">
                            <textarea class="form-control" placeholder="اكتب رسالتك هنا..." rows="1"></textarea>
                            <button class="btn btn-primary me-2" id="sendMessageBtn">إرسال</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <?php

} else {
    header("Location: index.php");
    exit;
}

include $temp . "footer.php";
