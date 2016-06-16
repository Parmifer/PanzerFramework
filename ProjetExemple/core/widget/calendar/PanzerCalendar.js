/* 
 * Copyright (C) 2016 lucile
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

$(document).ready(function () {
    function generateMonth(dateSelected) {
        var theDate = dateSelected;
        if (theDate === null || theDate === undefined) {
            theDate = new Date();
        }
        var months = new Array();
        months[0] = "Janvier";
        months[1] = "Février";
        months[2] = "Mars";
        months[3] = "Avril";
        months[4] = "Mai";
        months[5] = "Juin";
        months[6] = "Juillet";
        months[7] = "Août";
        months[8] = "Septembre";
        months[9] = "Octobre";
        months[10] = "Novembre";
        months[11] = "Décembre";
        var month = months[theDate.getMonth()];
        var year = theDate.getFullYear();

        // Set the title part
        $('#panzer-calendar-title-month').html(month);
        $('#panzer-calendar-title-year').html(year);



        // Get dates for the displayed month
        var bubbleDay = new Date(theDate.getFullYear(), theDate.getMonth(), 1, 1, 1, 1);
        // 2 to have week beginning on Monday
        bubbleDay.setDate(2 - bubbleDay.getDay());
        var days = new Array();
        for (var i = 0; i <= 35; i++) {
            days.push(new Date(bubbleDay));
            bubbleDay.setDate(bubbleDay.getDate() + 1);
        }

        // Set dates
        var week = 0;
        var dayInWeek = 0;
        days.forEach(function (day) {
            var button = $('#panzer-calendar-content-day-' + week + '-' + dayInWeek).find('button');
            if (day.getMonth() !== theDate.getMonth()) {
                // The date is not in the current month
                button.prop('disabled', true);
            } else {
                button.prop('disabled', false);
            }
            // Set the now date
            var nowDate = new Date();
            // Saturday === 6, Sunday = 0
            if (day.getDay() === 6 || day.getDay() === 0) {
                button.addClass('panzer-calendar-content-day-weekend');
            } else {
                button.removeClass('panzer-calendar-content-day-weekend');
            }
            if (day.getDate() === nowDate.getDate() && day.getMonth() === nowDate.getMonth() && day.getFullYear() === nowDate.getFullYear()) {
                button.addClass('panzer-calendar-content-day-current');
            } else {
                button.removeClass('panzer-calendar-content-day-current');
            }
            button.html(day.getDate());
            // Increment
            dayInWeek++;
            if (dayInWeek >= 7) {
                dayInWeek = 0;
                week++;
            }
        });

        return theDate;

    }
    var currentDate = generateMonth();
    $('#panzer-calendar-title-previous-arrow').on('click', function (event) {
        event.preventDefault();
        currentDate.setMonth(currentDate.getMonth() - 1);
        currentDate = generateMonth(currentDate);
    });
    $('#panzer-calendar-title-next-arrow').on('click', function (event) {
        event.preventDefault();
        currentDate.setMonth(currentDate.getMonth() + 1);
        currentDate = generateMonth(currentDate);
    });
});
