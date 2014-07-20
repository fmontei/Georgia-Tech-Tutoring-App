package controller;

import database.SQLLoginQuery;
import model.User;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import java.io.IOException;
import java.sql.SQLException;

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
                final User user = loginQuery.getUserByGTID(gtid, password);
                session.setAttribute("currentUser", user);
                session.removeAttribute("loginError");
                response.sendRedirect("jsp/menu.jsp");
            } catch (SQLException ex) {
                session.setAttribute("loginError", ex.getMessage());
                response.sendRedirect("jsp/login.jsp");
            }
        }
    }
}
