<?php
namespace App\Core\Sessions;

class Session
{
    private string $name = "";
    private array $data = [];
    private array $keysToRemove = [];
    private int $timeout = 0;

    /**
     * Creates a new Session class instance.
     * 
     * @param string $name The name of the session.
     */
    public function __construct(string $name)
    {
        StartSession();
        $this->name = $name;
    }

    /**
     * Sets or updates a specific key within the session.
     * 
     * @param string $name The name of the key you want to create.
     * @param mixed $value The data that the key will hold.
     */
    public function setKey(string $name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Gets the value of a specific key within the session.
     * 
     * @param $name The name of the key you want to retrieve.
     * 
     * @return mixed|null Either returns the data within the specified key,
     * or returns null when the key could not be found.
     */
    public function getKey(string $name)
    {
        return $this->data[$name];
    }

    /**
     * Removes a key from the session.
     * 
     * @param string $name The name of the key to remove.
     */
    public function removeKey(string $name)
    {
        if(!in_array($name, $this->keysToRemove))
            $this->keysToRemove[] = $name;
    }

    /**
     * Creates the session.
     * 
     * @return bool Returns true when the session could be created, and false
     * when the session could not be created.
     */
    public function create()
    {
        if(isset($_SESSION[$this->name]))
            return false;

        if($this->timeout > 0)
        {
            $this->data['app_session_timeout'] = [
                'duration' => $this->timeout,
                'initialized' => time()
            ];

            if(!isset($_SESSION['app_sessions_with_timeout']))
                $_SESSION['app_sessions_with_timeout'] = [];

            $_SESSION['app_sessions_with_timeout'][] = $this->name;
        }

        $_SESSION[$this->name] = $this->data;
        return true;
    }

    /**
     * Updates the session.
     * 
     * @return bool Returns true when the session is updated, and false
     * when the session could not be updated.
     */
    public function update()
    {
        if(!isset($_SESSION[$this->name]))
            return false;

        if(count($this->keysToRemove) > 0)
        {
            foreach($this->keysToRemove as $key)
            {
                if(isset($this->data[$key]))
                    unset($this->data[$key]);
            }
        }

        if($this->timeout > 0)
        {
            $this->data['app_session_timeout'] = [
                'duration' => $this->timeout,
                'initialized' => time()
            ];
        }

        if(count($this->data) > 0)
        {
            $_SESSION[$this->name] = [];
            foreach($this->data as $key => $value)
            {
                $_SESSION[$this->name][$key] = $value;
            }
        }

        return true;
    }

    /**
     * Destroys the session.
     */
    public function destroy()
    {
        if(isset($_SESSION[$this->name]))
            unset($_SESSION[$this->name]);
    }

    /**
     * Sets the lifetime of how long the session can exist.
     * 
     * @param int $seconds The amount of seconds the session can live.
     */
    public function setTimeout(int $seconds)
    {
        if($seconds > 0)
            $this->timeout = $seconds;
    }

    /**
     * Returns a new Session object from an existing session.
     * 
     * @param string $name The name of the session you want to retrieve.
     * @return Session|null Either returns a Session class instance, or null
     * when no session could be found.
     */
    public static function getExisting(string $name)
    {
        if(!isset($_SESSION[$name]))
            return null;

        $session = new Session($name);
        if(count($_SESSION[$name]) > 0)
        {
            // Check the timeout
            if(isset($_SESSION[$name]['app_session_timeout']))
            {
                if(self::sessionHasExpired($name))
                {
                    self::removeTimedOutSession($name);
                    return null;
                }

                $timeoutAmount =
                    $_SESSION[$name]['app_session_timeout']['duration'];

                $session->setTimeout($timeoutAmount);
            }

            if(count($_SESSION[$name]) > 0)
            {
                foreach($_SESSION[$name] as $key => $value)
                {
                    $session->setKey($key, $value);
                }
            }

            $session->update();
        }

        return $session;
    }

    /**
     * Removes all timed out session.
     */
    public static function removeAllTimedOutSessions()
    {
        if(!isset($_SESSION['app_sessions_with_timeout']))
            return;

        if(count($_SESSION['app_sessions_with_timeout']) <= 0)
        {
            unset($_SESSION['app_sessions_with_timeout']);
            return;
        }

        foreach($_SESSION['app_sessions_with_timeout'] as $name)
        {
            if(isset($_SESSION[$name]))
            {
                if(self::sessionHasExpired($name))
                {
                    self::removeTimedOutSession($name);
                }
                else
                {
                    self::refreshSessionTimeout($name);
                }
            }
        }
    }

    /**
     * Refreshes the timeout that is set on a session.
     * 
     * @param string $name The name of the session.
     */
    public static function refreshSessionTimeout(string $name)
    {
        if(!isset($_SESSION[$name]))
            return;

        if(count($_SESSION[$name]) <= 0)
            return;

        // Check the timeout
        if(isset($_SESSION[$name]['app_session_timeout']))
        {
            if(self::sessionHasExpired($name))
            {
                self::removeTimedOutSession($name);
                return;
            }

            if(!isset($_SESSION[$name]['app_session_timeout']['duration']))
                return;

            $timeoutAmount =
                $_SESSION[$name]['app_session_timeout']['duration'];

            $_SESSION[$name]['app_session_timeout'] = [
                'duration' => $timeoutAmount,
                'initialized' => time()
            ];
        }
    }

    /**
     * Checks if the session has timed out, provided it has a timeout.
     * 
     * @param string $name The name of the session.
     * 
     * @return bool Returns true if the session has timed out, or false
     * when the session hasn't.
     */
    private static function sessionHasExpired(string $name)
    {
        $timeoutArgs = $_SESSION[$name]['app_session_timeout'];

        if(
            !isset($timeoutArgs['duration']) ||
            !isset($timeoutArgs['initialized'])
        )
        {
            return true;
        }

        $timeDiff = time() - $timeoutArgs['initialized'];
        if($timeDiff > $timeoutArgs['duration'])
            return true;

        return false;
    }

    /**
     * Removes a timed out session.
     * 
     * @param string $name The name of the session.
     */
    private static function removeTimedOutSession(string $name)
    {
        // Unset the session and remove it from the global -
        // timeout session
        unset($_SESSION[$name]);

        $targetKey = 'app_sessions_with_timeout';
        $indexToRemove = array_search($name, $_SESSION[$targetKey]);
        if($indexToRemove !== false)
            array_splice($_SESSION[$targetKey], $indexToRemove, 1);
    }
}