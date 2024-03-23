package com.team055.buzzbid.repositories;

import com.team055.buzzbid.models.User;
import com.team055.buzzbid.repositories.mappers.UserRowMapper;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.jdbc.core.JdbcTemplate;
import org.springframework.stereotype.Component;

import java.sql.Connection;

@Component
public class UserRepository {
    private static final Logger log = LoggerFactory.getLogger(UserRepository.class);
    @Autowired
    JdbcTemplate jdbcTemplate;
    private final String table = "User";

    public boolean addUser(User user){
        log.debug("Inserting user record");
       int result = jdbcTemplate.update("INSERT INTO "+table+" (user_name, password, first_name, last_name)\n" +
                "VALUES (?, ?,?,?) ",
                user.getUserName(), user.getPassword(),user.getFirstName(),user.getLastName());
       return result > 0;
    }

    public User getUser(String userName){
        log.debug("Retrieving user record");
        String query = "SELECT * FROM "+table+" WHERE USER_NAME = ?";
        User user = jdbcTemplate.queryForObject(query, new UserRowMapper(), userName);
        return user;
    }
}
