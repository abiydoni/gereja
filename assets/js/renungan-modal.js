// JavaScript untuk Modal Renungan
class RenunganModal {
  constructor() {
    this.isLoading = false;
    this.currentId = null;
    this.init();
  }

  init() {
    // Initialize modal event listeners
    this.setupEventListeners();
    console.log("RenunganModal initialized");
  }

  setupEventListeners() {
    // Close modal when clicking outside
    const modal = document.getElementById("renunganModal");
    if (modal) {
      modal.addEventListener("click", (e) => {
        if (e.target === modal) {
          this.closeModal();
        }
      });
    }

    // Close modal with Escape key
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        this.closeModal();
      }
    });

    // Prevent modal close when clicking inside modal content
    const modalContent = document.querySelector("#renunganModal .bg-white");
    if (modalContent) {
      modalContent.addEventListener("click", (e) => {
        e.stopPropagation();
      });
    }
  }

  async showFullRenungan(id) {
    if (this.isLoading) {
      console.log("Request sedang diproses, tunggu sebentar...");
      return;
    }

    if (!id || id <= 0) {
      console.error("ID renungan tidak valid:", id);
      alert("ID renungan tidak valid");
      return;
    }

    this.isLoading = true;
    this.currentId = id;

    try {
      // Show loading state
      this.showLoadingState();

      // Fetch renungan data
      const response = await fetch(
        `../proses/get_renungan_working.php?id=${id}`
      );

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();

      if (data.success) {
        // Update modal content
        this.updateModalContent(data.renungan);

        // Show modal
        this.showModal();

        // Update view count in main page
        this.updateViewCount(id, data.renungan.views);

        console.log("Renungan berhasil dimuat:", data.renungan.judul);
      } else {
        throw new Error(data.message || "Gagal memuat renungan");
      }
    } catch (error) {
      console.error("Error loading renungan:", error);
      this.showError(error.message);
    } finally {
      this.isLoading = false;
      this.hideLoadingState();
    }
  }

  showLoadingState() {
    const modalTitle = document.getElementById("modalTitle");
    const modalContent = document.getElementById("modalContent");

    if (modalTitle) modalTitle.textContent = "Memuat...";
    if (modalContent) {
      modalContent.innerHTML = `
                <div class="flex items-center justify-center py-12">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-amber-600 mx-auto mb-4"></div>
                        <p class="text-gray-600">Memuat renungan...</p>
                    </div>
                </div>
            `;
    }
  }

  hideLoadingState() {
    // Loading state akan diganti dengan konten renungan
  }

  updateModalContent(renungan) {
    const modalTitle = document.getElementById("modalTitle");
    const modalContent = document.getElementById("modalContent");

    if (modalTitle) modalTitle.textContent = renungan.judul;
    if (modalContent) modalContent.innerHTML = renungan.konten;
  }

  showModal() {
    const modal = document.getElementById("renunganModal");
    if (modal) {
      modal.classList.remove("hidden");
      modal.classList.add("flex");

      // Add animation classes
      setTimeout(() => {
        modal.classList.add("opacity-100");
      }, 10);

      // Prevent body scroll
      document.body.style.overflow = "hidden";
    }
  }

  closeModal() {
    const modal = document.getElementById("renunganModal");
    if (modal) {
      // Add exit animation
      modal.classList.add("opacity-0");

      setTimeout(() => {
        modal.classList.add("hidden");
        modal.classList.remove("flex", "opacity-100", "opacity-0");

        // Restore body scroll
        document.body.style.overflow = "";

        // Clear content
        this.clearModalContent();
      }, 300);
    }
  }

  clearModalContent() {
    const modalTitle = document.getElementById("modalTitle");
    const modalContent = document.getElementById("modalContent");

    if (modalTitle) modalTitle.textContent = "";
    if (modalContent) modalContent.innerHTML = "";
  }

  updateViewCount(id, newViews) {
    try {
      const viewElement = document.querySelector(
        `[data-renungan-id="${id}"] .view-count`
      );
      if (viewElement) {
        // Format number with Indonesian locale
        const formattedViews = new Intl.NumberFormat("id-ID").format(newViews);
        viewElement.innerHTML = `<i class="fas fa-eye mr-1"></i>${formattedViews} dilihat`;

        // Add animation
        viewElement.classList.add("animate-pulse");
        setTimeout(() => {
          viewElement.classList.remove("animate-pulse");
        }, 1000);

        console.log(`View count updated for ID ${id}: ${newViews}`);
      } else {
        console.warn(`View count element not found for ID ${id}`);
      }
    } catch (error) {
      console.error("Error updating view count:", error);
    }
  }

  showError(message) {
    const modalTitle = document.getElementById("modalTitle");
    const modalContent = document.getElementById("modalContent");

    if (modalTitle) modalTitle.textContent = "Error";
    if (modalContent) {
      modalContent.innerHTML = `
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Terjadi Kesalahan</h3>
                    <p class="text-gray-600 mb-4">${message}</p>
                    <button onclick="renunganModal.closeModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Tutup
                    </button>
                </div>
            `;
    }

    // Show modal with error
    this.showModal();
  }

  // Utility methods
  isModalOpen() {
    const modal = document.getElementById("renunganModal");
    return modal && !modal.classList.contains("hidden");
  }

  getCurrentRenunganId() {
    return this.currentId;
  }
}

// Initialize modal when DOM is loaded
let renunganModal;

document.addEventListener("DOMContentLoaded", () => {
  renunganModal = new RenunganModal();

  // Make it globally accessible for onclick handlers
  window.renunganModal = renunganModal;

  console.log("RenunganModal DOM ready");
});

// Fallback function for onclick handlers (if class not ready)
function showFullRenungan(id) {
  if (renunganModal) {
    renunganModal.showFullRenungan(id);
  } else {
    console.error("RenunganModal not initialized");
    alert("Modal belum siap, silakan refresh halaman");
  }
}

function closeRenunganModal() {
  if (renunganModal) {
    renunganModal.closeModal();
  } else {
    console.error("RenunganModal not initialized");
  }
}

// Export for module usage
if (typeof module !== "undefined" && module.exports) {
  module.exports = RenunganModal;
}
