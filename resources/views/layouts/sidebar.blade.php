<ul class="nav flex-column bg-light p-3 h-100" style="min-width: 220px;">
    <li class="nav-item mb-2"><a href="{{ route('users.index') }}" class="nav-link">System Users</a></li>
    <li class="nav-item mb-2"><a href="{{ route('user-roles.index') }}" class="nav-link">User Roles</a></li>
    <li class="nav-item mb-2"><a href="{{ route('access-rights.index') }}" class="nav-link">User Rights & Privileges</a></li>
    <li class="nav-item mb-2"><a href="{{ route('designations.index') }}" class="nav-link">Designations</a></li>
    <li class="nav-item mb-2"><a href="{{ route('branches.index') }}" class="nav-link">Branches</a></li>
    <li class="nav-item mb-2"><a href="{{ route('departments.index') }}" class="nav-link">Departments</a></li>
    <li class="nav-item mb-2"><a href="{{ route('approval-matrices.index') }}" class="nav-link">Approval Matrix</a></li>
    <li class="nav-item mb-2"><a href="{{ route('approval-groups.index') }}" class="nav-link">Approval Groups</a></li>
    <li class="nav-item mb-2"><a href="{{ route('delegations.index') }}" class="nav-link">Delegation</a></li>
    @if(Auth::check())
        <li class="nav-item mb-2"><a href="{{ route('users.edit', Auth::id()) }}" class="nav-link">Account Settings</a></li>
        <li class="nav-item mb-2">
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-link nav-link" style="padding:0;">Logout</button>
            </form>
        </li>
    @endif
</ul> 