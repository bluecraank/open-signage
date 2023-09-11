<form action="{{ route('devices.register') }}" method="POST">
    @csrf
    <input type="text" name="secret" id="" placeholder="Secret Key">
    <button type="submit">Register device</button>
</form>

