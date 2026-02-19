<div id="contactUsModal" class="modal <?php echo $contact_us_modal_class; ?>">

    <div class="modal-overlay"></div>

    <div class="modal-content">

        <div class="close-btn-div">
            <span id='contactUsCloseBtn' class="close-btn">&times;</span>
        </div>

        <h2>Contact Us</h2>

        <div id='contactUsErrorMsg'>
            <?php if (!empty($contact_us_error_message)): ?>
                <div class="error-box">
                    <p class="error"><?php echo htmlspecialchars(
                        $contact_us_error_message,
                    ); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <form action="includes/contact_us_process.php" method="POST">
            <label for="contact_name">Name</label>
            <input type="text" id="contact_name" name="name" required placeholder="Your Name" value="<?php echo isset(
                $_SESSION["user_id"],
            )
                ? $_SESSION["username"]
                : ""; ?>" />

            <label for="contact_email">Email</label>
            <input type="email" id="contact_email" name="email" required placeholder="E-Mail" value="<?php echo isset(
                $_SESSION["user_id"],
            )
                ? $_SESSION["email"]
                : ""; ?>"/>

            <label for="contact_subject">Subject</label>
            <input type="text" id="contact_subject" name="subject" required placeholder="What is this about?" />

            <label for="contact_message">Message</label>
            <textarea id="contact_message" name="message" required placeholder="Write your message here..." rows="5"></textarea>

            <button type="submit" id="btnSubmit" class="btn-submit">Send Message</button>
        </form>
    </div>
</div>
