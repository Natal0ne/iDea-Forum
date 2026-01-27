<style>
    /* Main Footer Styles */
.footer-container {
    background-color: #50616d;
    color: #e0e0e0;
    padding: 40px 20px 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin-top: auto; /* Push to bottom if using flex/grid layout on body */
}

.footer-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
    gap: 20px;
}

.footer-section {
    flex: 1;
    min-width: 200px;
    margin-bottom: 20px;
}

.footer-section h3 {
    color: #ffffff;
    font-size: 1.2rem;
    margin-bottom: 15px;
    border-bottom: 2px solid #3498db;
    display: inline-block;
    padding-bottom: 5px;
}

.footer-section p {
    font-size: 0.9rem;
    line-height: 1.5;
    color: #bbbbbb;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin-bottom: 10px;
}

.footer-section ul li a {
    color: #bbbbbb;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-section ul li a:hover {
    color: #3498db;
    padding-left: 5px; /* Subtle movement effect */
}

/* Social Icons */
.social-icons {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.social-icons a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    background-color: #333;
    color: #fff;
    border-radius: 50%;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: bold;
    transition: background-color 0.3s, transform 0.3s;
}

.social-icons a:hover {
    background-color: #3498db;
    transform: translateY(-3px);
}

.newsletter-text {
    font-size: 0.85rem;
    margin-top: 10px;
}

/* Footer Bottom */
.footer-bottom {
    text-align: center;
    padding-top: 20px;
    margin-top: 20px;
    border-top: 1px solid #333;
    font-size: 0.85rem;
    color: #777;
}

/* Responsive Design */
@media (max-width: 768px) {
    .footer-content {
        flex-direction: column;
    }
    
    .footer-section {
        margin-bottom: 30px;
    }
}
</style>
<footer>
    <div class="footer-container">
        <div class="footer-content">
            <!-- Column 1: About -->
            <div class="footer-section about">
                <h3>iDea-Forum</h3>
                <p>La tua community online per condividere idee, discutere e connetterti con altri appassionati.</p>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="footer-section links">
                <h3>Esplora</h3>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Discussioni</a></li>
                    <li><a href="#">Membri</a></li>
                    <li><a href="#">Cerca</a></li>
                </ul>
            </div>

            <!-- Column 3: Legal/Help -->
            <div class="footer-section links">
                <h3>Supporto</h3>
                <ul>
                    <li><a href="#">Regolamento</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Contattaci</a></li>
                </ul>
            </div>

            <!-- Column 4: Socials -->
            <div class="footer-section social">
                <h3>Seguici</h3>
                <div class="social-icons">
                    <a href="#" aria-label="Facebook">FB</a>
                    <a href="#" aria-label="Twitter">TW</a>
                    <a href="#" aria-label="Instagram">IG</a>
                    <a href="#" aria-label="Discord">DS</a>
                </div>
                <p class="newsletter-text">Iscriviti alla nostra newsletter per novità!</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> iDea-Forum. Tutti i diritti riservati.</p>
        </div>
    </div>
</footer>