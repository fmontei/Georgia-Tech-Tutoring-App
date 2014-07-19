package controller;

import database.SQLLoginQuery;
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

@WebServlet(name = "LoginServlet", urlPatterns = {""})
public class LoginServlet extends HttpServlet {
    @Override
    public void doGet(HttpServletRequest request, HttpServletResponse response)
        throws IOException, ServletException {
        response.sendRedirect("jsp/login.jsp");
    }

    @Override
    public void doPost(HttpServletRequest request, HttpServletResponse response)
            throws IOException, ServletException {
        final HttpSession session = request.getSession();
        if (request.getParameter("loginButton") != null) {
            final String gtid = request.getParameter("user_gtid");
            final String password = request.getParameter("password");
            try {
                SQLLoginQuery loginQuery = new SQLLoginQuery();
                loginQuery.getUserByGTID(gtid, password);
                SQLTutorSearchQuery tutorSearchQuery = new SQLTutorSearchQuery();
                List<Course> courses = tutorSearchQuery.getAvailableStudentCourses(gtid);
                session.setAttribute("studentCourses", courses);
                session.removeAttribute("loginError");
                response.sendRedirect("jsp/menu.html");
            } catch (SQLException ex) {
                session.setAttribute("loginError", ex.getMessage());
                response.sendRedirect("jsp/login.jsp");
            }
        }
    }
}
