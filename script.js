/**
 * TRIPZONE - COMPLETE JAVASCRIPT
 * With Gallery, Bookings CRUD, Contact, Team Modal, and Developer Modal
 */

// Gallery Images
const galleryImages = [
    'assets/images/1.jpg',
    'assets/images/2.jpg',
    'assets/images/3.jpg',
    'assets/images/4.jpg',
    'assets/images/5.jpg',
    'assets/images/6.jpg',
    'assets/images/7.jpg',
    'assets/images/8.avif'
];

let currentUser = { id: null, name: null, email: null };

// ==================== GALLERY FUNCTIONS ====================

function initGallery() {
    const wrapper = document.getElementById('gallerySwiperWrapper');
    if (!wrapper) return;
    
    wrapper.innerHTML = '';
    galleryImages.forEach(src => {
        const slide = document.createElement('div');
        slide.className = 'swiper-slide';
        const img = document.createElement('img');
        img.src = src;
        img.alt = 'Travel Gallery';
        img.onerror = function() { 
            this.src = 'https://placehold.co/600x400/2A9D8F/white?text=TripZone'; 
        };
        slide.appendChild(img);
        slide.onclick = () => openLightbox(src);
        wrapper.appendChild(slide);
    });
    
    new Swiper('.gallery-swiper', {
        slidesPerView: 1,
        spaceBetween: 25,
        loop: true,
        pagination: { el: '.swiper-pagination', clickable: true, dynamicBullets: true },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        breakpoints: { 
            640: { slidesPerView: 2, spaceBetween: 20 },
            1024: { slidesPerView: 3, spaceBetween: 25 }
        },
        autoplay: { delay: 4000, disableOnInteraction: false }
    });
}

function openLightbox(src) {
    const lb = document.getElementById('lightbox');
    const img = document.getElementById('lightboxImg');
    if (lb && img) {
        img.src = src;
        lb.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeLightbox() {
    const lb = document.getElementById('lightbox');
    if (lb) {
        lb.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// ==================== USER FUNCTIONS ====================

function getUserInfo() {
    fetch('get_user.php')
        .then(response => response.json())
        .then(data => {
            if (data.logged_in) {
                currentUser = data.user;
                updateUI();
                loadMyBookings();
            }
        })
        .catch(error => console.log('Error:', error));
}

function updateUI() {
    const myBookingsNav = document.getElementById('myBookingsNav');
    if (currentUser && currentUser.name) {
        if (myBookingsNav) myBookingsNav.style.display = 'inline-block';
    } else {
        if (myBookingsNav) myBookingsNav.style.display = 'none';
    }
}

// ==================== TEAM MEMBER MODAL ====================

const teamMembers = {
    ebrahim: {
        name: "Md Ebrahim Hossain Nahid",
        id: "222-15-6400",
        department: "Computer Science and Engineering (CSE)",
        university: "Daffodil International University (DIU)",
        course: "Web Engineering Lab (CSE416)",
        year: "2026",
        email: "ebrahim15-6400@diu.edu.bd",
        role: "Team Leader & Backend Developer"
    },
    nahin: {
        name: "Md Ismail Hossain Nahin",
        id: "222-15-6401",
        department: "Computer Science and Engineering (CSE)",
        university: "Daffodil International University (DIU)",
        course: "Web Engineering Lab (CSE416)",
        year: "2026",
        email: "nahin15-6401@diu.edu.bd",
        role: "Frontend Developer & Database Designer"
    },
    shakib: {
        name: "Kazi Tanvir Ahmed Shakib",
        id: "222-15-6519",
        department: "Computer Science and Engineering (CSE)",
        university: "Daffodil International University (DIU)",
        course: "Web Engineering Lab (CSE416)",
        year: "2026",
        email: "shakib15-6519@diu.edu.bd",
        role: "UI/UX Designer & Tester"
    },
    naim: {
        name: "Md Naim Ahmmed",
        id: "222-15-6542",
        department: "Computer Science and Engineering (CSE)",
        university: "Daffodil International University (DIU)",
        course: "Web Engineering Lab (CSE416)",
        year: "2026",
        email: "naim15-6542@diu.edu.bd",
        role: "Content Writer & Documentation"
    }
};

function showTeamModal(memberId) {
    const member = teamMembers[memberId];
    if (!member) return;
    
    const modal = document.getElementById('teamModal');
    const modalMemberName = document.getElementById('modalMemberName');
    const teamInfoGrid = document.getElementById('teamInfoGrid');
    
    if (modalMemberName) {
        modalMemberName.textContent = member.name;
    }
    
    if (teamInfoGrid) {
        teamInfoGrid.innerHTML = `
            <div class="team-info-item">
                <label><i class="fas fa-id-badge"></i> Student ID</label>
                <p>${member.id}</p>
            </div>
            <div class="team-info-item">
                <label><i class="fas fa-graduation-cap"></i> Department</label>
                <p>${member.department}</p>
            </div>
            <div class="team-info-item">
                <label><i class="fas fa-university"></i> University</label>
                <p>${member.university}</p>
            </div>
            <div class="team-info-item">
                <label><i class="fas fa-code"></i> Course</label>
                <p>${member.course}</p>
            </div>
            <div class="team-info-item">
                <label><i class="fas fa-calendar-alt"></i> Project Year</label>
                <p>${member.year}</p>
            </div>
            <div class="team-info-item">
                <label><i class="fas fa-envelope"></i> Email</label>
                <p>${member.email}</p>
            </div>
            <div class="team-info-item">
                <label><i class="fas fa-tasks"></i> Role</label>
                <p>${member.role}</p>
            </div>
        `;
    }
    
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeTeamModal() {
    const modal = document.getElementById('teamModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// ==================== BOOKING CRUD FUNCTIONS ====================

// CREATE - Show booking form
function showBookingForm(packageName, price) {
    const html = `
        <div id="bookingModal" class="modal-overlay">
            <div class="modal-container">
                <div class="modal-header">
                    <h2><i class="fas fa-calendar-check"></i> Book ${packageName}</h2>
                    <span class="modal-close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Package</label>
                        <input type="text" value="${packageName}" readonly style="background:#f0f0f0;">
                    </div>
                    <div class="form-group">
                        <label>Price</label>
                        <input type="text" value="${price} BDT" readonly style="background:#f0f0f0;">
                    </div>
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" id="bookName" value="${currentUser.name}" required>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" id="bookEmail" value="${currentUser.email}" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number *</label>
                        <input type="tel" id="bookPhone" placeholder="Enter your phone number" required>
                    </div>
                    <div class="form-group">
                        <label>Travel Date *</label>
                        <input type="date" id="bookDate" required>
                    </div>
                    <div class="form-group">
                        <label>Number of People *</label>
                        <input type="number" id="bookPeople" min="1" max="20" value="1" required>
                    </div>
                    <div class="form-group">
                        <label>Special Requests</label>
                        <textarea id="bookRequests" rows="2" placeholder="Any special requests?"></textarea>
                    </div>
                    <button class="modal-submit-btn" id="confirmBooking">Confirm Booking</button>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', html);
    
    const modal = document.getElementById('bookingModal');
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('bookDate').min = today;
    
    document.getElementById('confirmBooking').onclick = async () => {
        const bookingData = {
            package_name: packageName,
            customer_name: document.getElementById('bookName').value.trim(),
            customer_email: document.getElementById('bookEmail').value.trim(),
            customer_phone: document.getElementById('bookPhone').value.trim(),
            travel_date: document.getElementById('bookDate').value,
            number_of_people: document.getElementById('bookPeople').value,
            special_requests: document.getElementById('bookRequests').value
        };
        
        if (!bookingData.customer_name || !bookingData.customer_email || !bookingData.customer_phone || !bookingData.travel_date) {
            alert('Please fill all required fields!');
            return;
        }
        
        const response = await fetch('booking_crud.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(bookingData)
        });
        const result = await response.json();
        alert(result.message);
        if (result.success) {
            modal.remove();
            loadMyBookings();
            document.getElementById('my-bookings').style.display = 'block';
            document.getElementById('my-bookings').scrollIntoView({ behavior: 'smooth' });
        }
    };
    
    modal.querySelector('.modal-close').onclick = () => modal.remove();
    modal.onclick = (e) => { if (e.target === modal) modal.remove(); };
}

// READ - Load all bookings
async function loadMyBookings() {
    if (!currentUser) return;
    
    try {
        const response = await fetch('get_bookings_crud.php');
        const data = await response.json();
        const container = document.getElementById('bookingsList');
        const section = document.getElementById('my-bookings');
        
        if (data.success && data.bookings && data.bookings.length > 0) {
            if (section) section.style.display = 'block';
            if (container) {
                container.innerHTML = '';
                data.bookings.forEach(booking => {
                    const card = document.createElement('div');
                    card.className = 'booking-card';
                    card.innerHTML = `
                        <div class="booking-header">
                            <div class="package-name">📦 ${escapeHtml(booking.package_name)}</div>
                            <div class="booking-status ${booking.status}">${booking.status}</div>
                        </div>
                        <div class="booking-details">
                            <p><i class="fas fa-user"></i> ${escapeHtml(booking.customer_name)}</p>
                            <p><i class="fas fa-phone"></i> ${escapeHtml(booking.customer_phone)}</p>
                            <p><i class="fas fa-calendar-alt"></i> Travel Date: ${new Date(booking.travel_date).toLocaleDateString()}</p>
                            <p><i class="fas fa-users"></i> ${booking.number_of_people} person(s)</p>
                            ${booking.special_requests ? `<p><i class="fas fa-comment"></i> ${escapeHtml(booking.special_requests)}</p>` : ''}
                            <p><i class="far fa-clock"></i> Booked on: ${new Date(booking.created_at).toLocaleString()}</p>
                        </div>
                        <div class="booking-actions">
                            <button class="edit-btn" data-id="${booking.id}" data-date="${booking.travel_date}" data-people="${booking.number_of_people}" data-requests="${escapeHtml(booking.special_requests || '')}"><i class="fas fa-edit"></i> Edit</button>
                            <button class="delete-btn" data-id="${booking.id}"><i class="fas fa-trash-alt"></i> Cancel</button>
                            <button class="view-btn" data-id="${booking.id}" data-package="${escapeHtml(booking.package_name)}" data-name="${escapeHtml(booking.customer_name)}" data-email="${escapeHtml(booking.customer_email)}" data-phone="${escapeHtml(booking.customer_phone)}" data-date="${booking.travel_date}" data-people="${booking.number_of_people}" data-requests="${escapeHtml(booking.special_requests || '')}" data-status="${booking.status}" data-created="${booking.created_at}"><i class="fas fa-eye"></i> View</button>
                        </div>
                    `;
                    container.appendChild(card);
                });
                attachBookingEvents();
            }
        } else {
            if (section) section.style.display = 'block';
            if (container) {
                container.innerHTML = '<div class="no-bookings"><i class="fas fa-calendar-alt"></i><p>No bookings yet. Book your first package!</p><button onclick="document.getElementById(\'packages\').scrollIntoView({behavior:\'smooth\'})" class="btn-primary" style="margin-top:15px;">Explore Packages</button></div>';
            }
        }
    } catch (error) {
        console.log('Error loading bookings:', error);
    }
}

// UPDATE - Show edit form
function showEditForm(bookingId, currentDate, currentPeople, currentRequests) {
    const html = `
        <div id="editModal" class="modal-overlay">
            <div class="modal-container">
                <div class="modal-header">
                    <h2><i class="fas fa-edit"></i> Update Booking</h2>
                    <span class="modal-close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>New Travel Date *</label>
                        <input type="date" id="editDate" value="${currentDate}" required>
                    </div>
                    <div class="form-group">
                        <label>Number of People *</label>
                        <input type="number" id="editPeople" min="1" max="20" value="${currentPeople}" required>
                    </div>
                    <div class="form-group">
                        <label>Special Requests</label>
                        <textarea id="editRequests" rows="2" placeholder="Any special requests?">${currentRequests}</textarea>
                    </div>
                    <button class="modal-submit-btn" id="confirmUpdate">Update Booking</button>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', html);
    
    const modal = document.getElementById('editModal');
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('editDate').min = today;
    
    document.getElementById('confirmUpdate').onclick = async () => {
        const updateData = {
            booking_id: bookingId,
            travel_date: document.getElementById('editDate').value,
            number_of_people: document.getElementById('editPeople').value,
            special_requests: document.getElementById('editRequests').value
        };
        
        const response = await fetch('update_booking.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(updateData)
        });
        const result = await response.json();
        alert(result.message);
        if (result.success) {
            modal.remove();
            loadMyBookings();
        }
    };
    
    modal.querySelector('.modal-close').onclick = () => modal.remove();
    modal.onclick = (e) => { if (e.target === modal) modal.remove(); };
}

// DELETE - Cancel booking
async function deleteBooking(bookingId) {
    if (confirm('⚠️ Are you sure you want to cancel this booking?\n\nThis action cannot be undone!')) {
        const response = await fetch('delete_booking.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ booking_id: bookingId })
        });
        const result = await response.json();
        alert(result.message);
        if (result.success) {
            loadMyBookings();
        }
    }
}

// VIEW - View booking details
function viewBookingDetails(packageName, customerName, customerEmail, customerPhone, travelDate, people, requests, status, createdAt) {
    const html = `
        <div id="viewModal" class="modal-overlay">
            <div class="modal-container">
                <div class="modal-header">
                    <h2><i class="fas fa-ticket-alt"></i> Booking Details</h2>
                    <span class="modal-close">&times;</span>
                </div>
                <div class="modal-body">
                    <div style="line-height: 2;">
                        <p><strong>📦 Package:</strong> ${escapeHtml(packageName)}</p>
                        <p><strong>👤 Customer Name:</strong> ${escapeHtml(customerName)}</p>
                        <p><strong>📧 Email:</strong> ${escapeHtml(customerEmail)}</p>
                        <p><strong>📞 Phone:</strong> ${escapeHtml(customerPhone)}</p>
                        <p><strong>📅 Travel Date:</strong> ${new Date(travelDate).toLocaleDateString()}</p>
                        <p><strong>👥 Number of People:</strong> ${people}</p>
                        ${requests ? `<p><strong>💬 Special Requests:</strong> ${escapeHtml(requests)}</p>` : ''}
                        <p><strong>✅ Status:</strong> <span class="booking-status ${status}">${status}</span></p>
                        <p><strong>📅 Booked On:</strong> ${new Date(createdAt).toLocaleString()}</p>
                    </div>
                    <button class="modal-submit-btn" id="closeViewBtn">Close</button>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', html);
    
    const modal = document.getElementById('viewModal');
    document.getElementById('closeViewBtn').onclick = () => modal.remove();
    modal.querySelector('.modal-close').onclick = () => modal.remove();
    modal.onclick = (e) => { if (e.target === modal) modal.remove(); };
}

function attachBookingEvents() {
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.onclick = () => {
            showEditForm(btn.dataset.id, btn.dataset.date, btn.dataset.people, btn.dataset.requests);
        };
    });
    
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.onclick = () => {
            deleteBooking(btn.dataset.id);
        };
    });
    
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.onclick = () => {
            viewBookingDetails(
                btn.dataset.package, btn.dataset.name, btn.dataset.email,
                btn.dataset.phone, btn.dataset.date, btn.dataset.people,
                btn.dataset.requests, btn.dataset.status, btn.dataset.created
            );
        };
    });
}

// ==================== CONTACT FUNCTIONS ====================

function sendMessage(name, email, subject, message) {
    fetch('contact.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'name=' + encodeURIComponent(name) + '&email=' + encodeURIComponent(email) + '&subject=' + encodeURIComponent(subject) + '&message=' + encodeURIComponent(message)
    })
    .then(response => response.json())
    .then(data => {
        const feedback = document.getElementById('formFeedback');
        if (feedback) {
            feedback.innerHTML = data.success ? '<span style="color:#2A9D8F;">✅ Message sent successfully!</span>' : '<span style="color:#E76F51;">❌ Failed to send message!</span>';
            setTimeout(() => feedback.innerHTML = '', 3000);
        }
        if (data.success) document.getElementById('messageForm').reset();
    })
    .catch(error => console.log('Error:', error));
}

// ==================== DEVELOPER MODAL ====================

function showDeveloperModal() {
    const modal = document.getElementById('developerModal');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeDeveloperModal() {
    const modal = document.getElementById('developerModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// ==================== HELPER FUNCTIONS ====================

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ==================== EVENT LISTENERS ====================

document.addEventListener('DOMContentLoaded', function() {
    console.log('TripZone Loading...');
    
    initGallery();
    getUserInfo();
    
    // Close lightbox
    document.querySelector('.close-lightbox')?.addEventListener('click', closeLightbox);
    document.getElementById('lightbox')?.addEventListener('click', (e) => { if (e.target === document.getElementById('lightbox')) closeLightbox(); });
    
    // Book buttons
    document.querySelectorAll('.book-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (!currentUser || !currentUser.name) {
                alert('Please login first to book a package!');
                window.location.href = 'login.php';
                return;
            }
            const packageCard = btn.closest('.package-card');
            const packageName = packageCard.querySelector('h3').innerText;
            const price = packageCard.dataset.price;
            showBookingForm(packageName, price);
        });
    });
    
    // Contact form
    const messageForm = document.getElementById('messageForm');
    if (messageForm) {
        messageForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const name = document.getElementById('fullName').value.trim();
            const email = document.getElementById('emailAddr').value.trim();
            const subject = document.getElementById('subjectMsg').value.trim();
            const message = document.getElementById('msgContent').value.trim();
            
            if (!name || !email || !subject || !message) {
                alert('All fields are required!');
                return;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Please enter a valid email address!');
                return;
            }
            
            sendMessage(name, email, subject, message);
        });
    }
    
    // Navigation - Mobile menu toggle
    const menuToggle = document.getElementById('menuToggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            const navLinks = document.querySelector('.nav-links');
            if (navLinks) navLinks.classList.toggle('active');
        });
    }
    
    // Explore packages button
    const exploreBtn = document.getElementById('exploreHeroBtn');
    if (exploreBtn) {
        exploreBtn.addEventListener('click', () => {
            const packagesSection = document.getElementById('packages');
            if (packagesSection) {
                packagesSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
    
    // My Bookings button
    const myBookingsBtn = document.getElementById('myBookingsNav');
    if (myBookingsBtn) {
        myBookingsBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!currentUser || !currentUser.name) {
                alert('Please login first to view your bookings!');
                window.location.href = 'login.php';
                return;
            }
            const bookingsSection = document.getElementById('my-bookings');
            if (bookingsSection) {
                bookingsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                loadMyBookings();
            }
        });
    }
    
    // Chatbot button
    const chatbotBtn = document.getElementById('chatbotBtn');
    if (chatbotBtn) {
        chatbotBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.location.href = 'chatbot.php';
        });
    }
    
    // Team card click handlers
    const teamCards = document.querySelectorAll('.team-card');
    teamCards.forEach(card => {
        card.addEventListener('click', () => {
            const memberId = card.getAttribute('data-member');
            if (memberId) {
                showTeamModal(memberId);
            }
        });
    });
    
    // Team modal close handlers
    const teamModal = document.getElementById('teamModal');
    if (teamModal) {
        const closeButtons = teamModal.querySelectorAll('.team-modal-close, .team-close-btn');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', closeTeamModal);
        });
        teamModal.addEventListener('click', (e) => {
            if (e.target === teamModal) closeTeamModal();
        });
    }
    
    // Developer modal
    const devBtn = document.getElementById('devInfoBtn');
    if (devBtn) devBtn.onclick = showDeveloperModal;
    
    const devModal = document.getElementById('developerModal');
    if (devModal) {
        devModal.querySelectorAll('.dev-modal-close, .dev-close-btn').forEach(btn => {
            btn.onclick = closeDeveloperModal;
        });
        devModal.onclick = (e) => { if (e.target === devModal) closeDeveloperModal(); };
    }
    
    // Scroll reveal animation
    const revealElements = document.querySelectorAll('.goal-card, .package-card, .contact-wrapper');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    revealElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
    
    console.log('✅ TripZone Loaded Successfully!');
    console.log('💰 Package Prices: Cox\'s Bazar: 7,500 BDT, Sajek: 7,500 BDT, Saint Martin: 10,000 BDT');
    console.log('👥 Team Members: 4 members loaded');
});