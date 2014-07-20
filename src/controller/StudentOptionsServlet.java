package controller;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import java.io.IOException;
import java.sql.SQLException;

@WebServlet(name = "StudentOptionsServlet", urlPatterns = {"/student_options"})
public class StudentOptionsServlet extends HttpServlet {
    @Override
    public void doGet(HttpServletRequest request, HttpServletResponse response)
            throws IOException, ServletException {
        response.sendRedirect("jsp/login.jsp");
    }

    @Override
    public void doPost(HttpServletRequest request, HttpServletResponse response)
            throws IOException, ServletException {
        final HttpSession session = request.getSession();
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
        /*try {

        } catch (SQLException ex) {
            response.sendRedirect("jsp/tutor_search.jsp");
        }*/
    }
}
