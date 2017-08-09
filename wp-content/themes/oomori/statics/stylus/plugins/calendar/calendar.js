function setupCalendars() {
    // Embedded Calendar
    Calendar.setup(
        {
            parentElement: 'embeddedCalendar'
        }
    )
}
Event.observe(window, 'load', function() { setupCalendars() })
