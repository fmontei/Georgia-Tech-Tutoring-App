package controller;

import database.SQLTutorSearchQuery;
import model.Course;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import java.io.IOException;
import java.sql.SQLException;
import java.util.List;

@WebServlet(name = "StudentOptionsServlet", urlPatterns = {"/student_options"})
public class StudentOptionsServlet extends HttpServlet {
    @Override
    public void doGet(HttpServletRequest request, HttpServletResponse response)
            throws IOException, ServletException {
        //response.sendRedirect("jsp/login.html");
    }

    @Override
    public void doPost(HttpServletRequest request, HttpServletResponse response)
            throws IOException, ServletException {
        if (request.getParameter("submitDayTimeBtn") != null) {
            gatherDaysAndTimes(request, response);
        }
    }

    private void gatherDaysAndTimes(final HttpServletRequest request,
                                    final HttpServletResponse response)
        throws IOException {
        final String schoolName = request.getParameter("schoolName");
        final String courseNum = request.getParameter("courseNumber");
        final int courseNumber = Integer.parseInt(courseNum);
        final String[] preferredDays = request.getParameterValues("preferredDay");
        final String[] preferredTimes = request.getParameterValues("preferredTime");
        final String[] formattedTimes = reformatTimes(preferredTimes);
        try {
            SQLTutorSearchQuery tutorSearchQuery = new SQLTutorSearchQuery();
            List<Course> foundCourses =
                tutorSearchQuery.getAvailableStudentCourses
                        (schoolName, courseNumber, preferredDays, formattedTimes);
            request.getSession().setAttribute("foundCourses", foundCourses);
        } catch (SQLException ex) {
        } finally {
            response.sendRedirect("jsp/tutor_search.html");
        }
    }

    private String[] reformatTimes(final String[] preferredTimes) {
        String[] formatted = new String[preferredTimes.length];
        for (int i = 0; i < preferredTimes.length; i++) {
            String formattedFirst = "", formattedLast = "";
            final int first = preferredTimes[i].indexOf(":");
            String firstHalf = preferredTimes[i].substring(0, first);
            final int time24Hour = Integer.parseInt(firstHalf);
            if (time24Hour > 12) {
                final int time12Hour = time24Hour - 12;
                formattedFirst += time12Hour;
                formattedLast = "pm";
            } else {
                formattedFirst += time24Hour;
                formattedLast = "am";
            }
            formatted[i] = formattedFirst + formattedLast;
        }
        return formatted;
    }
}
