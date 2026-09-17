// Calendar functionality

// Token CSRF publicado en <meta name="csrf-token"> por
// views/layouts/header.php; la API lo exige en la cabecera
// X-CSRF-Token para POST/PUT/DELETE (ver config/csrf.php).
function getCsrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.content || "";
}

document.addEventListener("DOMContentLoaded", function () {
  // Initialize calendar
  initializeCalendar();

  // Quick action buttons functionality
  const quickButtons = document.querySelectorAll(".quick-btn");
  quickButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const action = this.onclick;
      if (typeof action === "function") {
        action.call(this);
      }
    });
  });

  // Form validation for event form (if exists)
  const eventForm = document.getElementById("event-form");
  if (eventForm) {
    eventForm.addEventListener("submit", function (e) {
      e.preventDefault();
      submitEventForm(this);
    });
  }

  // Calendar day click functionality
  const calendarDays = document.querySelectorAll(".calendar-day[data-day]");
  calendarDays.forEach((day) => {
    day.addEventListener("click", function () {
      const selectedDate = this.dataset.day;
      fillDateInput(selectedDate);
      highlightSelectedDay(this);
    });
  });

  // Category selector change (multiple possible selects)
  const categorySelects = document.querySelectorAll(
    'select[name="categoria_id"]'
  );
  categorySelects.forEach((select) => {
    select.addEventListener("change", function () {
      updateCategoryPreview(this.value);
    });
  });
});

// Initialize calendar and load data
function initializeCalendar() {
  loadCategorias();
  setDefaultDate();

  // Initialize dynamic calendar if on calendar page
  if (document.getElementById("calendar-days")) {
    initializeDynamicCalendar();
  }
}

// Initialize dynamic calendar
function initializeDynamicCalendar() {
  // Set current date
  window.currentDate = new Date();
  window.currentMonth = window.currentDate.getMonth();
  window.currentYear = window.currentDate.getFullYear();

  // Load and display calendar
  updateCalendarDisplay();
  // loadCalendarEvents(); // Comentado - se maneja en calendary.php
}

// Change month navigation
function cambiarMes(direction) {
  window.currentMonth += direction;

  if (window.currentMonth > 11) {
    window.currentMonth = 0;
    window.currentYear++;
  } else if (window.currentMonth < 0) {
    window.currentMonth = 11;
    window.currentYear--;
  }

  updateCalendarDisplay();
  // loadCalendarEvents(); // Comentado - se maneja en calendary.php
}

// Update calendar display
function updateCalendarDisplay() {
  const monthNames = [
    "Enero",
    "Febrero",
    "Marzo",
    "Abril",
    "Mayo",
    "Junio",
    "Julio",
    "Agosto",
    "Septiembre",
    "Octubre",
    "Noviembre",
    "Diciembre",
  ];

  const monthYearElement = document.getElementById("calendar-month-year");
  if (monthYearElement) {
    monthYearElement.textContent = `${monthNames[window.currentMonth]} ${
      window.currentYear
    }`;
  }

  generateCalendarDays();
}

// Generate calendar days
function generateCalendarDays() {
  const calendarDays = document.getElementById("calendar-days");
  if (!calendarDays) return;

  calendarDays.innerHTML = "";

  // Get first day of month and number of days
  const firstDay = new Date(window.currentYear, window.currentMonth, 1);
  const lastDay = new Date(window.currentYear, window.currentMonth + 1, 0);
  const daysInMonth = lastDay.getDate();
  const startingDayOfWeek = firstDay.getDay();

  // Get today for comparison
  const today = new Date();
  const isCurrentMonth =
    today.getMonth() === window.currentMonth &&
    today.getFullYear() === window.currentYear;
  const todayDate = today.getDate();

  // Add empty cells for days before the first day of month
  for (let i = 0; i < startingDayOfWeek; i++) {
    const prevMonth = window.currentMonth === 0 ? 11 : window.currentMonth - 1;
    const prevYear =
      window.currentMonth === 0 ? window.currentYear - 1 : window.currentYear;
    const prevMonthLastDay = new Date(prevYear, prevMonth + 1, 0).getDate();
    const dayNum = prevMonthLastDay - startingDayOfWeek + i + 1;

    const dayElement = createDayElement(dayNum, true);
    calendarDays.appendChild(dayElement);
  }

  // Add days of current month
  for (let day = 1; day <= daysInMonth; day++) {
    const isToday = isCurrentMonth && day === todayDate;
    const dayElement = createDayElement(day, false, isToday);
    calendarDays.appendChild(dayElement);
  }

  // Add empty cells for days after the last day of month
  const totalCells = calendarDays.children.length;
  const remainingCells = 42 - totalCells; // 6 rows * 7 days

  for (let i = 1; i <= remainingCells; i++) {
    const dayElement = createDayElement(i, true);
    calendarDays.appendChild(dayElement);
  }
}

// Create day element
function createDayElement(dayNum, otherMonth = false, isToday = false) {
  const dayElement = document.createElement("div");
  dayElement.className = "calendar-day";

  if (otherMonth) {
    dayElement.classList.add("other-month");
  }

  if (isToday) {
    dayElement.classList.add("today");
  }

  dayElement.innerHTML = `
        <div class="day-number">${dayNum}</div>
        <div class="day-events" id="events-${dayNum}"></div>
    `;

  // Add click event to create new event
  if (!otherMonth) {
    dayElement.addEventListener("click", () => {
      const dateStr = `${window.currentYear}-${String(
        window.currentMonth + 1
      ).padStart(2, "0")}-${String(dayNum).padStart(2, "0")}`;
      window.location.href = `frmEvento.php?fecha=${dateStr}`;
    });
  }

  return dayElement;
}

// Load calendar events from API
async function loadCalendarEvents() {
  try {
    showCalendarLoading(true);

    const response = await fetch(
      `../api/obtener_eventos_calendario.php?mes=${
        window.currentMonth + 1
      }&anio=${window.currentYear}`,
      {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
      }
    );

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();

    if (data.success) {
      displayCalendarEvents(data.eventos_por_dia);
      updateCalendarLegend(data.estadisticas);
    } else {
      throw new Error(data.message || "Error al cargar eventos");
    }
  } catch (error) {
    console.error("Error al cargar eventos del calendario:", error);
    showNotification("Error al cargar los eventos del calendario", "error");
  } finally {
    showCalendarLoading(false);
  }
}

// Display events in calendar
function displayCalendarEvents(eventosPorDia) {
  // Clear existing events
  document.querySelectorAll(".day-events").forEach((container) => {
    container.innerHTML = "";
  });

  // Add events to respective days
  Object.keys(eventosPorDia).forEach((dia) => {
    const eventos = eventosPorDia[dia];
    const dayContainer = document.getElementById(`events-${dia}`);
    const dayElement = dayContainer?.parentElement;

    if (dayContainer && eventos.length > 0) {
      // Mark day as having events
      dayElement?.classList.add("has-events");

      // Show first 3 events, then "more" indicator
      const maxVisible = 3;
      eventos.slice(0, maxVisible).forEach((evento) => {
        const eventElement = createEventElement(evento);
        dayContainer.appendChild(eventElement);
      });

      // Add "more" indicator if needed
      if (eventos.length > maxVisible) {
        const moreElement = document.createElement("div");
        moreElement.className = "more-events";
        moreElement.textContent = `+${eventos.length - maxVisible} más`;
        moreElement.addEventListener("click", (e) => {
          e.stopPropagation();
          showDayEvents(dia, eventos);
        });
        dayContainer.appendChild(moreElement);
      }
    }
  });
}

// Create event element for calendar
function createEventElement(evento) {
  const eventElement = document.createElement("div");
  eventElement.className = `event-item tipo-${evento.tipo}`;
  eventElement.style.setProperty("--event-color", evento.color_display);
  eventElement.style.backgroundColor = evento.color_display;

  let eventText = "";
  if (evento.hora_display) {
    eventText += `<span class="event-time">${evento.hora_display}</span> `;
  }
  eventText += `<i class="${evento.icono}"></i> ${evento.titulo}`;

  eventElement.innerHTML = eventText;
  eventElement.title = `${evento.titulo}${
    evento.descripcion ? "\n" + evento.descripcion : ""
  }`;

  // Add click event to view/edit event
  eventElement.addEventListener("click", (e) => {
    e.stopPropagation();
    // Here you can add functionality to view/edit the event
    showNotification(`Evento: ${evento.titulo}`, "info");
  });

  return eventElement;
}

// Show calendar loading state
function showCalendarLoading(show) {
  const loading = document.querySelector(".calendar-loading");
  if (loading) {
    loading.style.display = show ? "block" : "none";
  }
}

// Show all events for a specific day
function showDayEvents(dia, eventos) {
  // This could open a modal or navigate to a detailed view
  const eventList = eventos
    .map((e) => `• ${e.hora_display || "Todo el día"}: ${e.titulo}`)
    .join("\n");
  alert(`Eventos del día ${dia}:\n\n${eventList}`);
}

// Load categories from database
async function loadCategorias() {
  try {
    showCategoryLoading(true);

    const response = await fetch("../../api/categories.php", {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();

    if (data.success && (data.categories || data.categorias)) {
      const categorias = data.categories || data.categorias;
      populateCategorySelect(categorias);
      updateCalendarLegend(categorias);
    } else {
      throw new Error(data.error || "Error al cargar categorías");
    }
  } catch (error) {
    console.error("Error al cargar categorías:", error);
    showNotification("Error al cargar las categorías", "error");

    // Fallback - crear categorías por defecto en el frontend
    const defaultCategories = [
      {
        id: 0,
        nombre: "Sin categoría",
        color: "#6b7280",
        descripcion: "Categoría por defecto",
      },
    ];
    populateCategorySelect(defaultCategories);
  } finally {
    showCategoryLoading(false);
  }
}

// Populate category select options
function populateCategorySelect(categorias) {
  const selects = document.querySelectorAll('select[name="categoria_id"]');

  selects.forEach((select) => {
    if (select) {
      // Keep the first option (placeholder)
      const placeholder = select.querySelector('option[value=""]');
      select.innerHTML = "";
      if (placeholder) {
        select.appendChild(placeholder);
      } else {
        select.innerHTML = '<option value="">Seleccionar categoría...</option>';
      }

      // Add categories
      categorias.forEach((categoria) => {
        const option = document.createElement("option");
        option.value = categoria.id;
        option.textContent = categoria.nombre;
        option.dataset.color = categoria.color;
        option.title = categoria.descripcion || categoria.nombre;
        select.appendChild(option);
      });
    }
  });
}

// Update calendar legend with categories
function updateCalendarLegend(estadisticas) {
  const legend = document.querySelector(".calendar-legend");
  if (legend && estadisticas) {
    legend.innerHTML = `
            <div class="legend-item">
                <i class="fas fa-calendar" style="color: #667eea;"></i>
                <span>Eventos (${estadisticas.eventos || 0})</span>
            </div>
            <div class="legend-item">
                <i class="fas fa-tasks" style="color: #10b981;"></i>
                <span>Tareas (${estadisticas.tareas || 0})</span>
            </div>
            <div class="legend-item">
                <i class="fas fa-bell" style="color: #f59e0b;"></i>
                <span>Recordatorios (${estadisticas.recordatorios || 0})</span>
            </div>
            <div class="legend-item">
                <i class="fas fa-list-check" style="color: #8b5cf6;"></i>
                <span>Total (${estadisticas.total_eventos || 0})</span>
            </div>
        `;
  }
}

// Show/hide category loading indicator
function showCategoryLoading(show) {
  const loading = document.querySelector(".category-loading");
  if (loading) {
    loading.style.display = show ? "block" : "none";
  }
}

// Set default date to today
function setDefaultDate() {
  const dateInputs = document.querySelectorAll('input[type="date"]');
  const today = new Date().toISOString().split("T")[0];

  dateInputs.forEach((input) => {
    if (!input.value) {
      input.value = today;
    }
  });
}

// Quick actions for calendar - redirect to form
function crearEventoHoy() {
  window.location.href = "frmEvento.php";
}

function crearEventoManana() {
  window.location.href = "frmEvento.php";
}

function verEventosHoy() {
  const today = new Date().toISOString().split("T")[0];
  // Esta función se puede expandir para mostrar eventos del día
  showNotification(`Mostrando eventos del ${today}`, "success");
}

function exportarCalendario() {
  showNotification("Función de exportar en desarrollo", "info");
}

// Submit event form
async function submitEventForm(form) {
  try {
    showNotification("Guardando evento...", "info");

    const formData = new FormData(form);
    const eventData = {
      titulo: formData.get("titulo"),
      descripcion: formData.get("descripcion"),
      fecha_inicio: formData.get("fecha_inicio"),
      hora_inicio: formData.get("hora_inicio"),
      hora_fin: formData.get("hora_fin"),
      tipo: formData.get("tipo"),
      categoria_id: parseInt(formData.get("categoria_id")),
    };

    // Validate required fields
    if (!eventData.titulo.trim()) {
      throw new Error("El título es requerido");
    }

    if (!eventData.fecha_inicio) {
      throw new Error("La fecha es requerida");
    }

    if (!eventData.categoria_id || eventData.categoria_id <= 0) {
      throw new Error("Debe seleccionar una categoría");
    }

    const response = await fetch("../../api/events.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-Token": getCsrfToken(),
      },
      body: JSON.stringify(eventData),
    });

    const result = await response.json();

    if (result.success) {
      showNotification("Evento creado exitosamente", "success");
      form.reset();
      setDefaultDate();
      // Redirigir al calendario para ver el evento creado
      setTimeout(() => {
        window.location.href = "calendary.php";
      }, 1500);
    } else {
      throw new Error(
        result.error || result.message || "Error al crear el evento"
      );
    }
  } catch (error) {
    console.error("Error al crear evento:", error);
    showNotification(error.message, "error");
  }
}

function fillDateInput(selectedDate) {
  const dateInput = document.getElementById("fecha");
  if (dateInput) {
    // Assuming selectedDate is in format 'YYYY-MM-DD'
    dateInput.value = selectedDate;
  }
}

function highlightSelectedDay(dayElement) {
  // Remove previous highlights
  document.querySelectorAll(".calendar-day.selected").forEach((day) => {
    day.classList.remove("selected");
  });

  // Add highlight to selected day
  dayElement.classList.add("selected");
}

function updateCategoryPreview(category) {
  // Add visual feedback for category selection
  const categorySelect = document.getElementById("categoria");
  if (categorySelect) {
    categorySelect.className = `form-control category-${category}`;
  }
}

// Calendar specific functions
function mostrarFormularioCategoria() {
  // Redirect to event form where categories can be managed
  window.location.href = "frmEvento.php";
}

// Form helper functions
function limpiarFormulario() {
  const form = document.getElementById("event-form");
  if (form) {
    form.reset();
    setDefaultDate();

    // Remove selected day highlight
    document.querySelectorAll(".calendar-day.selected").forEach((day) => {
      day.classList.remove("selected");
    });

    showNotification("Formulario limpiado", "success");
  }
}

function showNotification(message, type) {
  // Create notification element
  const notification = document.createElement("div");
  notification.className = `notification ${type}`;

  let icon;
  switch (type) {
    case "success":
      icon = "check-circle";
      break;
    case "error":
      icon = "exclamation-circle";
      break;
    case "info":
      icon = "info-circle";
      break;
    default:
      icon = "info-circle";
  }

  notification.innerHTML = `
        <i class="fas fa-${icon}"></i>
        <span>${message}</span>
    `;

  // Add to page
  document.body.appendChild(notification);

  // Show notification
  setTimeout(() => {
    notification.classList.add("show");
  }, 100);

  // Remove notification after 3 seconds
  setTimeout(() => {
    notification.classList.remove("show");
    setTimeout(() => {
      if (document.body.contains(notification)) {
        document.body.removeChild(notification);
      }
    }, 300);
  }, 3000);
}

// Dashboard functionality (if needed)
function initializeDashboard() {
  // Mobile menu toggle
  const mobileToggle = document.querySelector(".mobile-menu-toggle");
  const sidebar = document.querySelector(".sidebar");

  if (mobileToggle && sidebar) {
    mobileToggle.addEventListener("click", function () {
      sidebar.classList.toggle("mobile-open");
    });
  }

  // Stats cards animation
  const statsCards = document.querySelectorAll(".stats-card");
  statsCards.forEach((card, index) => {
    setTimeout(() => {
      card.style.opacity = "1";
      card.style.transform = "translateY(0)";
    }, index * 100);
  });
}
