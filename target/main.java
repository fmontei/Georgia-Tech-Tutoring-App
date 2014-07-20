import database.SQLLoginQuery;
import database.SQLTutorSearchQuery;
import model.User;

import java.sql.SQLException;

public class main {
    public static void main(String... args) {
        try {
            SQLLoginQuery loginQuery = new SQLLoginQuery();
            User user = loginQuery.getUserByGTID("000000003", "000000003");
            System.out.println("User gtid: " + user.getGTID());
            System.out.println("User type (should be student): " + user.getType());
            user = loginQuery.getUserByGTID("000000004", "000000004");
            System.out.println("User gtid: " + user.getGTID());
            System.out.println("User type (should be tutor): " + user.getType());
            user = loginQuery.getUserByGTID("000000020", "000000020");
            System.out.println("User gtid: " + user.getGTID());
            System.out.println("User type (should be admin): " + user.getType());
            user = loginQuery.getUserByGTID("000000021", "000000021");
            System.out.println("User gtid: " + user.getGTID());
            System.out.println("User type (should be professor): " + user.getType());
            SQLTutorSearchQuery tutorSearchQuery = new SQLTutorSearchQuery();
            tutorSearchQuery.getAvailableStudentCourses("000000020");
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
    }
}
