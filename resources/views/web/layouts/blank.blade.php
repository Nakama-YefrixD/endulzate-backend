@include('web.partials.head')
@include('web.partials.topnav')
@include('web.partials.sidebar')

@yield('contentWeb')
	<!-- Home -->

	@include('web.partials.footer')
</div>

@yield('scriptWeb')
</body>
</html>