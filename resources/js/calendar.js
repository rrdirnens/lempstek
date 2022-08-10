import Calendar from '@toast-ui/calendar'
import '@toast-ui/calendar/dist/toastui-calendar.min.css'; // Stylesheet for calendar

console.log('fuckiddy fuck')
console.log(Calendar)

const container = document.getElementById('calendar');
const options = {
  defaultView: 'week',
  calendars: [
    {
      id: 'cal1',
      name: 'Personal',
      backgroundColor: '#03bd9e',
    }
  ],
  week: {
    taskView: true,
    eventView: false
  },
};

const calendar = new Calendar(container, options);