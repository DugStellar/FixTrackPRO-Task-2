package com.capeitassist.fixtrackpro

import android.os.Bundle
import android.util.Patterns
import android.widget.Button
import android.widget.Toast
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import com.google.android.material.textfield.TextInputEditText
import com.google.android.material.textfield.TextInputLayout
import org.json.JSONObject
import java.io.BufferedReader
import java.io.InputStreamReader
import java.io.OutputStreamWriter
import java.net.HttpURLConnection
import java.net.URL
import java.net.URLEncoder
import kotlin.concurrent.thread

/**
 * RegisterActivity handles user registration by sending encrypted credentials to register.php.
 */
class RegisterActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_register)

        val rootView = findViewById<android.view.View>(R.id.registerLayout)
        ViewCompat.setOnApplyWindowInsetsListener(rootView) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }

        val nameEditText = findViewById<TextInputEditText>(R.id.regNameEditText)
        val emailEditText = findViewById<TextInputEditText>(R.id.regEmailEditText)
        val passwordEditText = findViewById<TextInputEditText>(R.id.regPasswordEditText)

        val nameInputLayout = findViewById<TextInputLayout>(R.id.regNameInputLayout)
        val emailInputLayout = findViewById<TextInputLayout>(R.id.regEmailInputLayout)
        val passwordInputLayout = findViewById<TextInputLayout>(R.id.regPasswordInputLayout)

        val submitButton = findViewById<Button>(R.id.registerSubmitButton)
        val backToLoginButton = findViewById<Button>(R.id.backToLoginButton)

        submitButton.setOnClickListener {
            val name = nameEditText.text.toString().trim()
            val email = emailEditText.text.toString().trim()
            val password = passwordEditText.text.toString().trim()

            var isValid = true

            if (name.isEmpty()) {
                nameInputLayout.error = "Full Name is required"
                isValid = false
            } else {
                nameInputLayout.error = null
            }

            if (email.isEmpty()) {
                emailInputLayout.error = "Email is required"
                isValid = false
            } else if (!Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
                emailInputLayout.error = "Enter a valid email address"
                isValid = false
            } else {
                emailInputLayout.error = null
            }

            if (password.length < 6) {
                passwordInputLayout.error = "Password must be at least 6 characters"
                isValid = false
            } else {
                passwordInputLayout.error = null
            }

            if (isValid) {
                performRegistration(name, email, password)
            }
        }

        backToLoginButton.setOnClickListener {
            finish()
        }
    }

    private fun performRegistration(name: String, email: String, pass: String) {
        thread {
            var conn: HttpURLConnection? = null
            try {
                val url = URL("${NetworkConfig.BASE_URL}register.php")
                conn = url.openConnection() as HttpURLConnection
                conn.requestMethod = "POST"
                conn.doOutput = true
                conn.connectTimeout = 7000
                conn.readTimeout = 7000
                conn.setRequestProperty("Content-Type", "application/x-www-form-urlencoded")

                val postData = "full_name=" + URLEncoder.encode(name, "UTF-8") +
                        "&email=" + URLEncoder.encode(email, "UTF-8") +
                        "&password=" + URLEncoder.encode(pass, "UTF-8")

                val writer = OutputStreamWriter(conn.outputStream)
                writer.write(postData)
                writer.flush()
                writer.close()

                val responseCode = conn.responseCode
                if (responseCode == HttpURLConnection.HTTP_OK) {
                    val reader = BufferedReader(InputStreamReader(conn.inputStream))
                    val response = StringBuilder()
                    var line: String?
                    while (reader.readLine().also { line = it } != null) {
                        response.append(line)
                    }
                    reader.close()

                    val jsonResponse = JSONObject(response.toString())
                    val status = jsonResponse.optString("status")
                    val message = jsonResponse.optString("message", "Registration response received")

                    runOnUiThread {
                        if (status == "success") {
                            Toast.makeText(this, message, Toast.LENGTH_LONG).show()
                            finish()
                        } else {
                            Toast.makeText(this, message, Toast.LENGTH_LONG).show()
                        }
                    }
                } else {
                    runOnUiThread {
                        Toast.makeText(this, "Server error: $responseCode", Toast.LENGTH_SHORT).show()
                    }
                }
            } catch (e: Exception) {
                e.printStackTrace()
                runOnUiThread {
                    Toast.makeText(this, "Connection failed. Check XAMPP server.", Toast.LENGTH_LONG).show()
                }
            } finally {
                conn?.disconnect()
            }
        }
    }
}