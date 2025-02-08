// Toggle Sidebar
const menuBtn = document.querySelector(".menu-btn");
const sidenav = document.querySelector(".sidenav");
const closeBtn = document.querySelector(".close-btn");

menuBtn.addEventListener("click", () => sidenav.classList.toggle("active"));
closeBtn.addEventListener("click", () => sidenav.classList.remove("active"));

// Close sidebar when clicking outside on mobile
document.addEventListener("click", (e) => {
  if (
    window.innerWidth <= 768 &&
    !sidenav.contains(e.target) &&
    !menuBtn.contains(e.target)
  ) {
    sidenav.classList.remove("active");
  }
});

// Chart Implementation
const ctx = document.getElementById("flowChart").getContext("2d");
new Chart(ctx, {
  type: "line",
  data: {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
    datasets: [
      {
        label: "Hospital Occupancy",
        data: [65, 59, 80, 81, 56, 55],
        borderColor: "#3498db",
        tension: 0.4,
      },
      {
        label: "Vaccine Availability",
        data: [28, 48, 40, 19, 86, 27],
        borderColor: "#2ecc71",
        tension: 0.4,
      },
      {
        label: "Beds Availability",
        data: [35, 25, 45, 65, 30, 70],
        borderColor: "#e74c3c",
        tension: 0.4,
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
  },
});


