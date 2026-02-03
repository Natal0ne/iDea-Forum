<div id="signUpModal" class="modal hidden">

    <div class="modal-overlay"></div>

    <div class="modal-content">
        <div class="close-btn-div">
            <span id='signUpCloseBtn' class="close-btn" data-target="signUpModal"
                onclick="this.closest('.modal').classList.add('hidden')">&times;</span>
        </div>

        <h2>Sign up</h2>
        <form action="/includes/sign_up_process.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required placeholder="Username" />
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required placeholder="E-Mail" />
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="Password" />
            <button type="submit" class="btn-submit">Sign up</button>
        </form>
        <p>
            Already on iDea? <a href="#" id="signInBtn">Sign in</a>
        </p>
    </div>
</div>