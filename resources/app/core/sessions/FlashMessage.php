<?php
namespace App\Core\Sessions;

class FlashMessage
{
    private string $name = "";
    private array $data = [];
    private array $keysToRemove = [];

    /**
     * Creates a new Session class instance.
     * 
     * @param string $name The name of the session.
     */
    public function __construct(string $name)
    {
        StartSession();

        $this->name = $name;
        $this->createdAt = microtime(true);

        if(!isset($_SESSION['app_flashdata_key_created']))
            $_SESSION['app_flashdata_key_created'] = uniqid(microtime(), true);
    }

    public function create()
    {
        if(!isset($_SESSION['app_flashdata']))
            $_SESSION['app_flashdata'] = [];

        if(count($this->keysToRemove) > 0)
        {
            foreach($this->keysToRemove as $key)
            {
                if(isset($this->data[$key]))
                    unset($this->data[$key]);
            }
        }

        $_SESSION['app_flashdata'][$this->name] = $this->data;
        $_SESSION['app_flashdata'][$this->name]['app_flashdata_key_created']
            = $_SESSION['app_flashdata_key_created'];
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
     * Returns a new Session object from an existing session.
     * 
     * @param string $name The name of the session you want to retrieve.
     * @return FlashMessage|null Either returns a FlashMessage class instance, 
     * or null when no session could be found.
     */
    public static function getExisting(string $name)
    {
        if(!isset($_SESSION['app_flashdata']))
            return null;

        if(!isset($_SESSION['app_flashdata'][$name]))
            return null;

        $flashMessage = new FlashMessage($name);
        if(count($_SESSION['app_flashdata'][$name]) > 0)
        {
            foreach($_SESSION['app_flashdata'][$name] as $key => $var)
            {
                if($key == "app_flashdata_key_created")
                    continue;

                $flashMessage->setKey($key, $var);
            }

        }
        
        return $flashMessage;
    }

    /**
     * Destroys all old messages.
     * This function needs to be called in order for the flash messages to work.
     */
    public static function destroyAllOldMessages()
    {
        if(isset($_SESSION['app_flashdata_key_created']))
        {
            $currentFlashKey = $_SESSION['app_flashdata_key_created'];
            foreach($_SESSION['app_flashdata'] as $sessionKey => $sessionData)
            {
                $sessionFlashKey = $sessionData['app_flashdata_key_created'];
                if($sessionFlashKey != $currentFlashKey)
                    unset($_SESSION['app_flashdata'][$sessionKey]);
            }

            unset($_SESSION['app_flashdata_key_created']);
        }
        else
        {
            unset($_SESSION['app_flashdata']);
        }
    }
}